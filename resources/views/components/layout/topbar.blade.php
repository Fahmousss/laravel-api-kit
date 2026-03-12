<nav class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200 sticky top-0 z-20 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <span class="font-bold text-lg tracking-wide text-emerald-950">{{ config('app.name') }}</span>
    </div>
    @auth
    <div class="flex items-center gap-6">
        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full uppercase tracking-widest border border-emerald-200">
            {{ \Illuminate\Support\Str::headline(auth()->user()->primary_role ?? 'User') }}
        </span>
        <form method="POST" action="{{ route('web.logout') }}">
            @csrf
            <button type="submit" class="bg-transparent border border-slate-300 text-slate-500 px-4 py-1.5 rounded-md text-sm font-medium hover:text-slate-800 hover:border-emerald-300 hover:bg-slate-50 transition-all">
                Logout
            </button>
        </form>
    </div>
    @endauth
</nav>
