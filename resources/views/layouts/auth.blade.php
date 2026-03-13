@props([
    'title' => null,
    'description' => null,
    'image' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} - {{ $title }}</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        {{-- Scripts and Styles --}}
        @vite(['resources/css/app.css'])

        <script>
            const loadDarkMode = () => {
                const theme = localStorage.getItem('theme') ?? 'system'
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark')
                }
            }
            loadDarkMode();
        </script>
    </head>
    <body class="antialiased font-['Plus_Jakarta_Sans',sans-serif] bg-white dark:bg-neutral-950 text-zinc-900 dark:text-zinc-100 min-h-screen">
        <div class="flex min-h-screen">
            {{-- Visual Column --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-neutral-900">
                <div class="absolute inset-0 z-0">
                    <img 
                        src="{{ $image ?? asset('assets/images/auth-branding.png') }}" 
                        alt="Branding" 
                        class="w-full h-full object-cover opacity-50 transition-opacity duration-700"
                    >
                    <div class="absolute inset-0 bg-linear-to-br from-emerald-600/40 to-teal-900/60 mix-blend-multiply"></div>
                </div>

                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <x-ui.icon name="bolt" variant="solid" class="w-6 h-6 text-white" />
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-white">{{ config('app.name') }}</span>
                    </div>

                    <div class="max-w-md">
                        <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                            Build faster with modern architectural patterns.
                        </h2>
                        <p class="text-emerald-50/80 text-lg">
                            The ultimate Laravel starter kit designed for scalability and developer happiness.
                        </p>
                    </div>

                    <div class="text-sm text-emerald-100/50">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </div>
                </div>
            </div>

            {{-- Form Column --}}
            <div class="flex-1 flex flex-col items-center justify-center p-6 lg:p-12 bg-white dark:bg-neutral-950">
                <div class="w-full max-w-[440px]">
                    {{-- Mobile Logo --}}
                    <div class="lg:hidden flex items-center justify-center gap-2 mb-12">
                        <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                            <x-ui.icon name="bolt" variant="solid" class="w-6 h-6 text-white" />
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-zinc-900 dark:text-white">{{ config('app.name') }}</span>
                    </div>

                    <div class="mb-8 text-center_">
                         <h1 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
                            {{ $title }}
                        </h1>
                        @if($description)
                            <p class="text-zinc-500 dark:text-neutral-400 mt-2">
                            {{ $description }}
                            </p>
                        @endif
                    </div>

                    <div class="space-y-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        @vite(['resources/js/app.js'])
        <script>loadDarkMode()</script>
    </body>
</html>
