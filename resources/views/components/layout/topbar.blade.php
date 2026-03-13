<div class="navbar bg-base-100 border-b border-base-300 sticky top-0 z-20 backdrop-blur-md px-6">
    <div class="flex-1">
        <a href="{{ route('home') }}" class="btn btn-ghost text-xl font-bold tracking-wide text-primary normal-case">
            {{ config('app.name') }}
        </a>
    </div>
    
    @auth
    <div class="flex-none gap-4">
        <div class="hidden sm:block">
            <span class="text-sm font-semibold opacity-70">
                {{ $authenticatedUser->displayName }}
            </span>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm btn-error">
                Logout
            </button>
        </form>
    </div>
    @endauth
</div>
