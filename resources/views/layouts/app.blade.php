<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} - @yield('title', 'Welcome')</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">


        {{-- Scripts and Styles --}}
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
    <body class="antialiased font-['Plus_Jakarta_Sans',sans-serif] bg-slate-50 dark:bg-neutral-950 text-zinc-900 dark:text-zinc-100 min-h-screen">
        @auth
            <x-ui.layout variant="sidebar-main">
                <x-ui.sidebar sticky-header scrollable>
                    <x-slot:brand>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition-colors">
                                <x-ui.icon name="bolt" variant="solid" class="w-5 h-5 text-white" />
                            </div>
                            <span data-slot="brand-name" class="font-bold text-lg tracking-tight text-zinc-900 dark:text-white">API Kit</span>
                        </a>
                    </x-slot:brand>

                    <x-ui.navlist>
                            <x-ui.navlist.item
                                icon="home"
                                label="Dashboard"
                                :href="route('dashboard')"
                                :active="request()->routeIs('dashboard')"
                            />
                            <x-ui.navlist.item
                                icon="user"
                                label="Profile"
                                href="#"
                            />
                            <x-ui.navlist.item
                                icon="cog-8-tooth"
                                label="Settings"
                                href="#"
                            />
                    </x-ui.navlist>
                </x-ui.sidebar>

                <x-ui.layout.main>
                    <x-ui.layout.header sticky>
                        <div class="flex items-center gap-2 md:hidden">
                            <x-ui.sidebar.toggle />
                        </div>

                        <div class="flex-1 flex justify-end px-4 gap-4 items-center">
                            <x-ui.theme-switcher variant="inline" />
                            <x-ui.navbar>
                                <x-ui.dropdown >
                                    <x-slot:button>
                                        <x-ui.button variant="soft" iconAfter="chevron-down" size="sm" class="gap-x-2">
                                            <x-ui.avatar name="Circle" color="blue" circle size="sm"/>
                                        </x-ui.button>
                                    </x-slot:button>

                                    <x-slot:menu>
                                        <x-ui.dropdown.item icon="user-circle">
                                            My Profile
                                        </x-ui.dropdown.item>
                                        <x-ui.dropdown.item icon="cog-6-tooth">
                                            Settings
                                        </x-ui.dropdown.item>

                                        <x-ui.dropdown.separator />

                                        <x-ui.dropdown.item
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <x-ui.icon name="arrow-left-on-rectangle" class="text-red-600 dark:text-red-400" />
                                                    <span class="text-red-600 dark:text-red-400">Logout</span>
                                                </div>
                                            </form>
                                        </x-ui.dropdown.item>
                                    </x-slot:menu>
                                </x-ui.dropdown>
                            </x-ui.navbar>
                        </div>
                    </x-ui.layout.header>

                    <div class="p-4 sm:p-8 max-w-7xl mx-auto">
                        @yield('content')
                    </div>
                </x-ui.layout.main>
            </x-ui.layout>
        @else
            <main class="min-h-screen">
                @yield('content')
            </main>
        @endauth

        @vite(['resources/js/app.js'])
        <script>
            loadDarkMode()
        </script>
    </body>
</html>
