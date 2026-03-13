@props(['links' => []])

<aside class="w-64 border-r border-base-300 bg-base-100 flex flex-col fixed inset-y-0 left-0 pt-16 z-10 transition-transform hidden sm:flex">
    <div class="p-4 flex-1 overflow-y-auto">
        <ul class="menu w-full p-0">
            @if(count($links) > 0)
                @foreach($links as $link)
                    @php
                        $isActive = request()->fullUrlIs($link['url']) || request()->is(ltrim(parse_url($link['url'], PHP_URL_PATH), '/') . '*');
                    @endphp
                    <li>
                        <a href="{{ $link['url'] }}" @class(['active' => $isActive])>
                            @if(isset($link['icon']))
                                {!! $link['icon'] !!}
                            @else
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            @endif
                            <span class="font-medium">{{ $link['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            @else
                <li class="menu-title text-xs uppercase tracking-widest font-bold opacity-40 mt-2 mb-2">Menu</li>
                {{ $slot }}
            @endif
        </ul>
    </div>
</aside>
