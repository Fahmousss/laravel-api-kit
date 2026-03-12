@extends('layouts.app')

@section('title', 'Welcome to Stunting Map')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <style>
        .marker-cluster-safe { background-color: rgba(16, 185, 129, 0.6); border: 2px solid #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .marker-cluster-warning { background-color: rgba(245, 158, 11, 0.6); border: 2px solid #f59e0b; color: white; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; }
        .marker-cluster-danger { background-color: rgba(244, 63, 94, 0.6); border: 2px solid #f43f5e; color: white; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-emerald-950 mb-1">Stunting Distribution Map</h1>
            <p class="text-slate-500">Public geographical overview of stunting cases.</p>
        </div>
        
        <form method="GET" action="{{ route('web.home') }}" class="flex items-center gap-3 w-full md:w-auto">
            <x-form.select 
                name="kelurahan" 
                label="" 
                :options="$viewModel->kelurahans->mapWithKeys(fn($item) => [$item => $item])->toArray()" 
                :selected="$viewModel->selectedKelurahan"
                class="min-w-50 mb-0"
                onchange="this.form.submit()"
            />
            @if($viewModel->selectedKelurahan)
                <a href="{{ route('web.home') }}" class="text-sm text-slate-500 hover:text-rose-500 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-widest mb-1">Total Geo-Tagged</h3>
            <p class="text-3xl font-bold text-emerald-950">{{ count($viewModel->geoSummary) }}</p>
        </div>
        <div class="bg-white border text-center border-amber-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-amber-500 uppercase tracking-widest mb-1">Stunted</h3>
            <p class="text-3xl font-bold text-amber-500">{{ $viewModel->getTotalStunting() }}</p>
        </div>
        <div class="bg-white border text-center border-rose-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-rose-500 uppercase tracking-widest mb-1">Severely Stunted</h3>
            <p class="text-3xl font-bold text-rose-500">{{ $viewModel->getTotalSevereStunting() }}</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div id="stunting-map" class="w-full h-150 rounded-xl border border-slate-200 z-0 bg-slate-50"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('stunting-map').setView([-2.9909, 104.7565], 13); // Palembang default

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        var markers = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            iconCreateFunction: function(cluster) {
                var childCount = cluster.getChildCount();
                var children = cluster.getAllChildMarkers();
                
                var stuntingCount = 0;
                children.forEach(function(marker) {
                    if (marker.options.stuntingStatus === 'Stunted' || marker.options.stuntingStatus === 'Severely Stunted' || marker.options.stuntingStatus === 'Terindikasi Stunting') {
                        stuntingCount++;
                    }
                });
                
                var percentage = Math.round((stuntingCount / childCount) * 100);
                
                var c = ' marker-cluster-';
                if (percentage < 10) {
                    c += 'safe';
                } else if (percentage < 25) {
                    c += 'warning';
                } else {
                    c += 'danger';
                }

                return new L.DivIcon({ 
                    html: '<div class="flex flex-col items-center justify-center h-full w-full"><span>' + percentage + '%</span></div>', 
                    className: 'marker-cluster' + c, 
                    iconSize: new L.Point(44, 44) 
                });
            }
        });

        var geoData = @json($viewModel->geoSummary);

        const iconStunted = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const iconSeverelyStunted = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const iconNormal = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        let bounds = [];

        geoData.forEach(function(point) {
            var selectedIcon = iconNormal;
            if (point.status === 'Severely Stunted') selectedIcon = iconSeverelyStunted;
            if (point.status === 'Stunted' || point.status === 'Terindikasi Stunting') selectedIcon = iconStunted;

            var marker = L.marker([point.lat, point.lng], {
                icon: selectedIcon,
                stuntingStatus: point.status 
            });
            
            marker.bindPopup(`<b>Status:</b> ${point.status}`);
            markers.addLayer(marker);
            bounds.push([point.lat, point.lng]);
        });

        map.addLayer(markers);
        
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    });
</script>
@endsection
