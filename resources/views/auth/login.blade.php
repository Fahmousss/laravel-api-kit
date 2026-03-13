
<x-layout-auth 
    title="Welcome back" 
    description="Please enter your details to sign in"
>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-form.input
            label="Email Address"
            name="email"
            type="email"
            placeholder="you@example.com"
            required
            autofocus
        />

        <div class="space-y-1">
            <x-form.input
                label="Password"
                name="password"
                type="password"
                placeholder="••••••••"
                required
            />
            <div class="flex justify-end">
                <a href="{{ route('password.request') }}" class="text-xs text-emerald-600 hover:text-emerald-500 font-medium">
                    Forgot password?
                </a>
            </div>
        </div>

        <x-form.button label="Sign In" />
    </form>

    <x-ui.separator label="or" />
    <p class="text-center text-sm text-zinc-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-500 font-semibold">Sign up</a>
    </p>
</x-layout-auth>
