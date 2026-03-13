
<x-layout-auth 
    title="Create an account" 
    description="Join us today to get started"
>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-form.input
            label="Full Name"
            name="name"
            placeholder="John Doe"
            required
            autofocus
        />

        <x-form.input
            label="Email Address"
            name="email"
            type="email"
            placeholder="you@example.com"
            required
        />

        <x-form.input
            label="Password"
            name="password"
            type="password"
            placeholder="••••••••"
            required
        />

        <x-form.input
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            placeholder="••••••••"
            required
        />

        <x-form.button label="Create Account" />
    </form>

    <x-ui.separator label="or" />
    <p class="text-center text-sm text-zinc-500">
        Already have an account?
        <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-500 font-semibold">Sign in</a>
    </p>
</x-layout-auth>
