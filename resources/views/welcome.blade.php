<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css'])

        <script>
        // Load dark mode before page renders to prevent flicker
        const loadDarkMode = () => {
            const theme = localStorage.getItem('theme') ?? 'system'
            
            if (
                theme === 'dark' ||
                (theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)')
                    .matches)
            ) {
                document.documentElement.classList.add('dark')
            }
        }
                
        // Initialize on page load
        loadDarkMode();
    </script>
    </head>
    <body class="antialiased bg-white dark:bg-neutral-950 font-['Plus_Jakarta_Sans',sans-serif]">
        <div class="relative min-h-screen">
            <header class="p-6 flex items-center justify-between max-w-7xl mx-auto w-full absolute top-0 left-0 right-0 z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <x-ui.icon name="bolt" variant="solid" class="w-5 h-5 text-white" />
                    </div>
                    <span class="font-bold text-lg tracking-tight text-zinc-900 dark:text-white">{{ config('app.name') }}</span>
                </div>

                <div class="flex items-center gap-4">

                    @auth
                            <x-ui.button variant="ghost" size="sm" href="{{url('dashboard')}}">Dashboard</x-ui.button>

                    @else
                        <a href="{{ route('login') }}">
                            <x-ui.button variant="ghost" size="sm">Sign in</x-ui.button>
                        </a>
                        <a href="{{ route('register') }}">
                            <x-ui.button variant="primary" size="sm">Get Started</x-ui.button>
                        </a>
                    @endauth
                </div>
            </header>

            <main class="flex items-center justify-center min-h-screen p-6">
                <div class="max-w-2xl w-full text-center space-y-8">
                    <div class="space-y-4">
                        <x-ui.badge variant="outline" color="emerald" class="uppercase tracking-widest text-[10px] py-1 px-3">
                            <span class="relative flex size-2 mr-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                            </span>
                            Available Now</x-ui.badge>
                        <h1 class="text-5xl sm:text-7xl font-extrabold text-zinc-900 dark:text-white leading-[1.1]">
                            Build faster with <span class="text-transparent bg-clip-text bg-linear-to-r from-emerald-600 to-teal-500">Modern Architecture</span>
                        </h1>
                        <p class="text-zinc-500 dark:text-neutral-400 text-lg max-w-lg mx-auto leading-relaxed">
                            The ultimate Laravel starter kit with Domain-Driven Design, CQRS, and Sheaf UI.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <x-ui.button size="lg" icon-after="arrow-right" >
                            Start Building Now
                        </x-ui.button>
                        <x-ui.button variant="outline" size="lg" >
                            View Documentation
                        </x-ui.button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8 pt-12">
                        <div class="space-y-1">
                            <span class="block text-2xl font-bold text-zinc-900 dark:text-white">100%</span>
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Type Safe</span>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-2xl font-bold text-zinc-900 dark:text-white">Onion</span>
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Architecture</span>
                        </div>
                        <div class="space-y-1 col-span-2 md:col-span-1">
                            <span class="block text-2xl font-bold text-zinc-900 dark:text-white">Alpine.js</span>
                            <span class="text-xs text-zinc-400 uppercase tracking-widest font-semibold">Driven UI</span>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        @vite(['resources/js/app.js'])
        <script>
            loadDarkMode()
        </script>
    </body>
</html>
