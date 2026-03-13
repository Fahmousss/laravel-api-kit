<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="bg-base-200 text-base-content min-h-screen font-sans antialiased text-sm">
    <x-layout.topbar />
    
    <div class="flex min-h-[calc(100vh-65px)]">
        @auth
            <x-layout.sidebar>
                {{-- Navigation links will be populated by views extending this layout --}}
                @yield('sidebar')
            </x-layout.sidebar>
        @endauth
        
        <main class="flex-1 p-8 @auth sm:ml-64 @endauth">
            @yield('content')
        </main>
    </div>
</body>
</html>
