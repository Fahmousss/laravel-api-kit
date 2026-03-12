@extends('layouts.app')

@section('sidebar')
    <x-layout.sidebar :links="[
        ['url' => route('kader.dashboard'), 'name' => 'Dashboard'],
        ['url' => route('kader.children.index'), 'name' => 'Register Child'],
        ['url' => route('kader.measurements.create'), 'name' => 'Add Measurement'],
    ]" />
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-emerald-950 mb-2">Kader Dashboard</h1>
    <p class="text-slate-500">Welcome, {{ $user->name }}. Here are your assigned Posyandus.</p>
</div>

<!-- Geo-Tagging Map & Stats -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm flex flex-col">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-emerald-950">Stunting Distribution Map</h2>
                <p class="text-sm text-slate-500">Geospatial overview of stunting cases in your assigned Posyandu</p>
            </div>
        </div>
        
        <div id="stunting-map" class="w-full h-125 bg-slate-50 z-0"></div>
    </div>
    
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 bg-rose-500/20 text-rose-500 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-emerald-950 mb-1">{{ $dashboardViewModel->totalSevereStunting }}</h3>
            <p class="text-sm text-slate-500 font-medium">Severely Stunted</p>
        </div>
        
        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 bg-amber-500/20 text-amber-500 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-emerald-950 mb-1">{{ $dashboardViewModel->totalStunting }}</h3>
            <p class="text-sm text-slate-500 font-medium">Stunted</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Palembang center roughly
        var map = L.map('stunting-map').setView([-2.9909, 104.7565], 13);

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
                    if (marker.options.stuntingStatus === 'Stunted' || marker.options.stuntingStatus === 'Severely Stunted') {
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
                    iconSize: new L.Point(40, 40) 
                });
            }
        });

        // Add custom CSS for our clusters to override defaults if needed
        var style = document.createElement('style');
        style.innerHTML = `
            .marker-cluster-safe { background-color: rgba(16, 185, 129, 0.6); border: 2px solid #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; }
            .marker-cluster-warning { background-color: rgba(245, 158, 11, 0.6); border: 2px solid #f59e0b; color: white; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; }
            .marker-cluster-danger { background-color: rgba(244, 63, 94, 0.6); border: 2px solid #f43f5e; color: white; border-radius: 50%; display: flex; align-items: center; justify-center; font-weight: bold; }
        `;
        document.head.appendChild(style);

        var geoData = @json($dashboardViewModel->geoSummary);

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

        geoData.forEach(function(point) {
            var selectedIcon = iconNormal;
            if (point.status === 'Severely Stunted') selectedIcon = iconSeverelyStunted;
            if (point.status === 'Stunted') selectedIcon = iconStunted;

            var marker = L.marker([point.lat, point.lng], {
                icon: selectedIcon,
                stuntingStatus: point.status // Custom property for the cluster icon function
            });
            
            marker.bindPopup(`<b>Measurement ID:</b> ${point.id}<br><b>Status:</b> ${point.status}`);
            markers.addLayer(marker);
        });

        map.addLayer(markers);
        
        // Fit bounds to show all markers if any exist
        if (geoData.length > 0) {
            map.fitBounds(markers.getBounds(), { padding: [50, 50] });
        }
    });
</script>

<x-data.table :headers="['ID', 'Name', 'District', 'Location', 'Coordinates', 'Assigned At']" :paginator="$viewModel->paginator">
    @forelse($viewModel->posyandus as $posyandu)
        <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">{{ $posyandu->id }}</td>
            <td class="px-6 py-4 font-medium text-emerald-950">{{ $posyandu->name }}</td>
            <td class="px-6 py-4">{{ $posyandu->district }}</td>
            <td class="px-6 py-4">{{ $posyandu->location }}</td>
            <td class="px-6 py-4 text-xs font-mono text-slate-500">
                @if($posyandu->lat && $posyandu->lng)
                    {{ $posyandu->lat }}, {{ $posyandu->lng }}
                @else
                    N/A
                @endif
            </td>
            <td class="px-6 py-4">{{ $posyandu->createdAtFormatted }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                You have not been assigned to any Posyandu yet.<br>
                Please contact an Administrator to get assigned.
            </td>
        </tr>
    @endforelse
    
    <x-slot:footer>
        <x-data.pagination :paginator="$viewModel->paginator" />
    </x-slot:footer>
</x-data.table>
@endsection
