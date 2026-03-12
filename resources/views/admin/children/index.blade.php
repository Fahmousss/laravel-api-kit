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
        <h1 class="text-2xl font-bold text-emerald-950 mb-2">Children Data Registry</h1>
        <p class="text-slate-500">View and manage a comprehensive list of all children across all Posyandus globally.</p>
    </div>
</div>

<div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
    <x-data.table :headers="['NIK', 'Name', 'Age', 'Gender', 'Parent', 'Posyandu ID']" :paginator="$paginator">
        @forelse($paginator as $child)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 text-slate-700 font-mono text-sm">{{ $child->nik }}</td>
                <td class="px-6 py-4 font-medium text-emerald-950">{{ $child->name }}</td>
                <td class="px-6 py-4 text-slate-700">{{ $child->ageString }}</td>
                <td class="px-6 py-4 text-indigo-300">{{ $child->gender }}</td>
                <td class="px-6 py-4 text-slate-700">{{ $child->parentName }}</td>
                <td class="px-6 py-4 text-slate-500">#{{ $child->posyanduId }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                    No children records found.
                </td>
            </tr>
        @endforelse

        <x-slot:footer>
            <x-data.pagination :paginator="$paginator" />
        </x-slot:footer>
    </x-data.table>
</div>
@endsection
