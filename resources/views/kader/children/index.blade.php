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
    <h1 class="text-2xl font-bold text-emerald-950 mb-2">Child Registration</h1>
    <p class="text-slate-500">Manage and register children in your assigned Posyandus.</p>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Left Column: Selection & List --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-slate-100 rounded-2xl p-6">
            <form action="{{ route('kader.children.index') }}" method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <x-form.select 
                        name="posyandu_id" 
                        label="Select Posyandu" 
                        :options="$posyanduOptions" 
                        :selected="$selectedPosyanduId" 
                        onchange="this.form.submit()"
                    />
                </div>
                <!-- Remove button margin adjustment from select -->
                <div class="mb-4">
                    <noscript>
                        <x-form.button>Apply</x-form.button>
                    </noscript>
                </div>
            </form>
        </div>

        @if($selectedPosyanduId)
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
                <x-data.table :headers="['NIK', 'Name', 'DOB', 'Gender', 'Parent']">
                    @forelse($viewModel?->children ?? [] as $child)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-mono text-xs">{{ $child->nik }}</td>
                            <td class="px-6 py-4 font-medium text-emerald-950">{{ $child->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $child->dobFormatted }}</td>
                            <td class="px-6 py-4">{{ $child->gender }}</td>
                            <td class="px-6 py-4">{{ $child->parentName }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No children registered in this Posyandu.
                            </td>
                        </tr>
                    @endforelse
                </x-data.table>
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-2xl p-8 text-center text-slate-500">
                Please select a Posyandu to view registered children.
            </div>
        @endif
    </div>

    {{-- Right Column: Registration Form --}}
    <div class="lg:col-span-1">
        @if($selectedPosyanduId)
            <div class="bg-white border border-slate-100 rounded-2xl p-6 sticky top-24">
                <h2 class="text-lg font-bold text-emerald-950 mb-4">Register New Child</h2>
                <form action="{{ route('kader.children.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="posyandu_id" value="{{ $selectedPosyanduId }}">

                    <x-form.input name="nik" label="NIK" type="text" maxlength="16" placeholder="16-digit NIK" required />
                    
                    <x-form.input name="name" label="Full Name" type="text" placeholder="Child's full name" required />
                    
                    <x-form.input name="dob" label="Date of Birth" type="date" required />
                    
                    <x-form.select name="gender" label="Gender" :options="['L' => 'Male (Laki-laki)', 'P' => 'Female (Perempuan)']" required />
                    
                    <x-form.input name="parent_name" label="Parent's Name" type="text" placeholder="Mother or Father's name" required />
                    
                    <div class="pt-2">
                        <x-form.button class="w-full">Register Child</x-form.button>
                    </div>
                </form>
            </div>
        @endif
    </div>

</div>
@endsection
