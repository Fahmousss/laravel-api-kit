@extends('layouts.app')

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
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-emerald-950 mb-2">Manage Posyandus</h1>
        <p class="text-slate-500">View and create Master Data for Posyandu clinics across districts.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Left: Posyandu List --}}
    <div class="lg:col-span-2">
        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
            <x-data.table :headers="['Name', 'District', 'Location', 'Geo']" :paginator="$viewModel->paginator">
                @forelse($viewModel->posyandus as $posyandu)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-emerald-950">{{ $posyandu->name }}</td>
                        <td class="px-6 py-4">{{ $posyandu->district }}</td>
                        <td class="px-6 py-4 truncate max-w-xs" title="{{ $posyandu->location }}">{{ Str::limit($posyandu->location, 30) }}</td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-500">
                            {{ $posyandu->lat && $posyandu->lng ? $posyandu->lat . ', ' . $posyandu->lng : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            No posyandus found.
                        </td>
                    </tr>
                @endforelse

                <x-slot:footer>
                    <x-data.pagination :paginator="$viewModel->paginator" />
                </x-slot:footer>
            </x-data.table>
        </div>
    </div>

    {{-- Right: Create Form --}}
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-100 rounded-2xl p-6 sticky top-24">
            <h2 class="text-lg font-bold text-emerald-950 mb-4">Add New Posyandu</h2>
            <form action="{{ route('admin.posyandus.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-form.input name="name" label="Posyandu Name" type="text" placeholder="e.g. Mawar 1" required />
                <x-form.input name="district" label="District" type="text" placeholder="e.g. Menteng" required />
                <x-form.textarea name="location" label="Location Detail" placeholder="Full address..." rows="2" />
                
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input name="lat" label="Latitude" type="number" step="0.0000001" placeholder="-6.2" />
                    <x-form.input name="lng" label="Longitude" type="number" step="0.0000001" placeholder="106.8" />
                </div>
                
                <div class="pt-2">
                    <x-form.button class="w-full">Save Posyandu</x-form.button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
