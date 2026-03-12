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
    <h1 class="text-2xl font-bold text-emerald-950 mb-2">Record Measurement</h1>
    <p class="text-slate-500">Input height, weight, and geo-location for a child's stunting evaluation.</p>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl">

    {{-- Left: Context Selection Form --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-6 h-max">
        <h2 class="text-lg font-bold text-emerald-950 mb-4">Step 1: Select Child</h2>
        
        <form action="{{ route('kader.measurements.create') }}" method="GET" class="space-y-4">
            <x-form.select 
                name="posyandu_id" 
                label="Assigned Posyandu" 
                :options="$posyanduOptions" 
                :selected="$selectedPosyanduId" 
                onchange="this.form.submit()"
            />
            <noscript>
                <div class="-mt-2 mb-4">
                    <x-form.button>Load Children</x-form.button>
                </div>
            </noscript>
        </form>
    </div>

    {{-- Right: Measurement Input Form --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-6">
        <h2 class="text-lg font-bold text-emerald-950 mb-4">Step 2: Enter Data</h2>
        
        @if($selectedPosyanduId && count($childOptions) > 0)
            <form action="{{ route('kader.measurements.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="posyandu_id" value="{{ $selectedPosyanduId }}">

                <x-form.select name="child_id" label="Child" :options="$childOptions" required />
                
                <x-form.input name="measurement_date" label="Date of Measurement" type="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input name="height_cm" label="Height (cm)" type="number" step="0.1" placeholder="e.g. 75.5" required />
                    <x-form.input name="weight_kg" label="Weight (kg)" type="number" step="0.1" placeholder="e.g. 9.2" />
                </div>
                
                <x-form.select name="position" label="Posisi Pengukuran" :options="['telentang' => 'Telentang (0-24 Bulan)', 'berdiri' => 'Berdiri (>24 Bulan)']" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input name="lat" label="Latitude (Optional)" type="number" step="0.0000001" placeholder="-6.200000" id="lat_input" />
                    <x-form.input name="lng" label="Longitude (Optional)" type="number" step="0.0000001" placeholder="106.816666" id="lng_input" />
                </div>
                
                <div class="pt-2 text-right">
                    <button type="button" onclick="getLocation()" class="text-xs text-emerald-700 hover:text-indigo-300 font-medium tracking-wide mr-4">
                        <span class="mr-1">📍</span> Get Current Location
                    </button>
                    <x-form.button>Save Measurement</x-form.button>
                </div>
            </form>

            <script>
                function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            document.getElementById('lat_input').value = position.coords.latitude;
                            document.getElementById('lng_input').value = position.coords.longitude;
                        }, function(error) {
                            alert("Geolocation failed: " + error.message);
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                }
            </script>
        @elseif($selectedPosyanduId)
            <div class="py-12 text-center text-slate-500">
                No children found for this Posyandu.
            </div>
        @else
            <div class="py-12 text-center text-slate-500">
                Please select a Posyandu first.
            </div>
        @endif
    </div>

</div>
@endsection
