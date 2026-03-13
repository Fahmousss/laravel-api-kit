<nav class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200 sticky top-0 z-20 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <span class="font-bold text-lg tracking-wide text-emerald-950">{{ config('app.name') }}</span>
    </div>
    @auth
    <div class="flex items-center gap-6">
        <span class="text-sm font-medium text-slate-700">
            {{ $authenticatedUser->displayName }}
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-transparent border border-slate-300 text-slate-500 px-4 py-1.5 rounded-md text-sm font-medium hover:text-slate-800 hover:border-emerald-300 hover:bg-slate-50 transition-all">
                Logout
            </button>
        </form>
    </div>
    @endauth
</nav>
