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
        <h1 class="text-2xl font-bold text-emerald-950 mb-2">Global Measurement Records</h1>
        <p class="text-slate-500">View detailed anthropometric measurements submitted by Kaders globally.</p>
    </div>
</div>

<div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
    <x-data.table :headers="['Date', 'Child ID', 'Height (cm)', 'Weight (kg)', 'Geo-Tag', 'Status', 'Z-Score']" :paginator="$paginator">
        @forelse($paginator as $measurement)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 text-slate-700 font-mono text-sm">{{ Carbon\Carbon::parse($measurement->date)->format('d M Y') }}</td>
                <td class="px-6 py-4 font-medium text-emerald-950">#{{ $measurement->childId }}</td>
                <td class="px-6 py-4 text-slate-700">{{ $measurement->height }}</td>
                <td class="px-6 py-4 text-slate-700">{{ $measurement->weight ?? '-' }}</td>
                <td class="px-6 py-4">
                    @if($measurement->lat && $measurement->lng)
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs border border-emerald-200">
                            {{ round($measurement->lat, 4) }}, {{ round($measurement->lng, 4) }}
                        </span>
                    @else
                        <span class="text-slate-500 text-xs">-</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($measurement->status === 'Severely Stunted')
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-rose-500/10 text-rose-400 text-xs border border-rose-500/20">Severely Stunted</span>
                    @elseif($measurement->status === 'Stunted')
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-500/10 text-amber-400 text-xs border border-amber-500/20">Stunted</span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-400 text-xs border border-emerald-500/20">Normal</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-mono text-sm {{ $measurement->zScore < -2 ? 'text-rose-400' : 'text-slate-500' }}">
                    {{ number_format((float) $measurement->zScore, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                    No measurement records found.
                </td>
            </tr>
        @endforelse

        <x-slot:footer>
            <x-data.pagination :paginator="$paginator" />
        </x-slot:footer>
    </x-data.table>
</div>
@endsection
