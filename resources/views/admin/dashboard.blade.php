@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
@endpush

@section('sidebar')
    <x-layout.sidebar :links="[
        ['url' => route('admin.dashboard'), 'name' => 'Dashboard'],
        ['url' => route('admin.posyandus.index'), 'name' => 'Posyandus'],
        ['url' => route('admin.users.index'), 'name' => 'Users'],
        ['url' => route('admin.children.index'), 'name' => 'Children'],
        ['url' => route('admin.measurements.index'), 'name' => 'Measurements'],
    ]" />
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-emerald-950 mb-2">Admin Dashboard</h1>
    <p class="text-slate-500">Welcome, {{ $user->name }}. Complete overview of stunting geographical distribution.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-emerald-200 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-widest mb-1">Total Geo-Tagged</h3>
        <p class="text-3xl font-bold text-emerald-950">{{ count($viewModel->geoSummary) }}</p>
    </div>
    <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-rose-400 uppercase tracking-widest mb-1">Severely Stunted</h3>
        <p class="text-3xl font-bold text-rose-500">{{ $viewModel->totalSevereStunting }}</p>
    </div>
    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-6">
        <h3 class="text-sm font-semibold text-amber-400 uppercase tracking-widest mb-1">Stunted</h3>
        <p class="text-3xl font-bold text-amber-500">{{ $viewModel->totalStunting }}</p>
    </div>
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-6 flex flex-col justify-center">
        <p class="text-sm text-slate-700">
            Map clusters display <strong>stunting percentage</strong> per area. <span class="text-rose-400">Red</span> bounds denote high risk regions.
        </p>
    </div>
</div>

<div class="bg-white border border-slate-100 rounded-2xl p-6">
    <h2 class="text-lg font-bold text-emerald-950 mb-4">Stunting Distribution Map (Clustered)</h2>
    <div id="stunting-map" class="w-full h-125 rounded-xl border border-slate-200 z-0"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('stunting-map').setView([-2.5, 118.0], 5);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        const summaries = @json($viewModel->geoSummary);
        
        const severeIcon = L.divIcon({ className: 'custom-div-icon', html: "<div style='background-color:#f43f5e;width:12px;height:12px;border-radius:50%;border:2px solid white;'></div>", iconSize: [12, 12] });
        const stuntedIcon = L.divIcon({ className: 'custom-div-icon', html: "<div style='background-color:#f59e0b;width:12px;height:12px;border-radius:50%;border:2px solid white;'></div>", iconSize: [12, 12] });
        const normalIcon = L.divIcon({ className: 'custom-div-icon', html: "<div style='background-color:#10b981;width:10px;height:10px;border-radius:50%;border:2px solid white;'></div>", iconSize: [10, 10] });

        // Initialize MarkerClusterGroup with custom icon creation to calculate percentage
        const markers = L.markerClusterGroup({
            maxClusterRadius: 50,
            iconCreateFunction: function(cluster) {
                const childMarkers = cluster.getAllChildMarkers();
                let stuntedCount = 0;
                
                childMarkers.forEach(function(marker) {
                    const status = marker.options.stuntingStatus;
                    if (status === 'Severely Stunted' || status === 'Terindikasi Stunting' || status === 'Stunted') {
                        stuntedCount++;
                    }
                });

                const percentage = Math.round((stuntedCount / childMarkers.length) * 100);
                
                let bgColor = '#10b981'; // Green
                if (percentage >= 15) bgColor = '#f59e0b'; // Yellow
                if (percentage >= 30) bgColor = '#f43f5e'; // Red
                
                const html = `<div style="background-color: ${bgColor}; color: white; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid rgba(255,255,255,0.3); font-size: 13px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);">
                    ${percentage}%
                </div>`;
                
                return L.divIcon({ html: html, className: 'custom-cluster-icon', iconSize: L.point(44, 44) });
            }
        });

        let bounds = [];

        summaries.forEach(function(point) {
            if (point.lat && point.lng) {
                let icon = normalIcon;
                if (point.status === 'Severely Stunted') icon = severeIcon;
                else if (point.status === 'Stunted' || point.status === 'Terindikasi Stunting') icon = stuntedIcon;

                // Pass the status down into the marker's options so the cluster algorithm can aggregate it
                const marker = L.marker([point.lat, point.lng], {
                    icon: icon,
                    stuntingStatus: point.status
                });
                
                marker.bindPopup("<b>Status:</b> " + point.status + "<br><b>Child ID:</b> " + point.id);
                markers.addLayer(marker);
                bounds.push([point.lat, point.lng]);
            }
        });

        map.addLayer(markers);

        if (bounds.length > 0) {
            map.fitBounds(bounds, {padding: [50, 50]});
        }
    });
</script>
@endsection
