
<x-layout-auth 
    title="Forgot password" 
    description="No problem. Just let us know your email address and we will email you a password reset link."
>
    @if (session('status'))
        <x-ui.alerts variant="success" class="mb-4">
            {{ session('status') }}
        </x-ui.alerts>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-form.input 
            label="Email Address" 
            name="email" 
            type="email" 
            placeholder="you@example.com" 
            required 
            autofocus 
        />

        <x-form.button label="Email Password Reset Link" />
    </form>

    <x-ui.separator label="or" />
    <p class="text-center text-sm text-zinc-500">
        Remember your password?
        <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-500 font-semibold">Sign in</a>
    </p>
</x-layout-auth>
