@props(['headers' => []])

<div class="overflow-x-auto bg-slate-50 border border-slate-200 rounded-xl shadow-xl">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-semibold tracking-wider">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-6 py-4">{{ $header }}</th>
                @endforeach
                {{-- Optional extra header slot for Actions --}}
                @if(isset($extraHeaders))
                    {{ $extraHeaders }}
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 text-sm text-slate-800">
            {{ $slot }}
        </tbody>
    </table>
    
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $footer }}
        </div>
    @endif
</div>
