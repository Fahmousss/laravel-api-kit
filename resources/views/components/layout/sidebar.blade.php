@props(['links' => []])

<aside class="w-64 border-r border-slate-200 bg-white flex flex-col fixed inset-y-0 left-0 pt-18.25 z-10 transition-transform">
    <div class="px-4 py-6 flex-1 overflow-y-auto w-full">
        @if(count($links) > 0)
            <nav class="space-y-1">
                @foreach($links as $link)
                    @php
                        $isActive = request()->fullUrlIs($link['url']) || request()->is(ltrim(parse_url($link['url'], PHP_URL_PATH), '/') . '*');
                    @endphp
                    <a href="{{ $link['url'] }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors w-full
                              {{ $isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 border border-transparent' }}">
                        
                        @if(isset($link['icon']))
                            {!! $link['icon'] !!}
                        @else
                            <svg class="w-5 h-5 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        @endif
                        {{ $link['name'] }}
                    </a>
                @endforeach
            </nav>
        @else
            <div class="text-xs text-slate-500 uppercase tracking-widest px-3 mb-2 font-semibold">Menu</div>
            <div class="space-y-1">
                {{ $slot }}
            </div>
        @endif
    </div>
</aside>
