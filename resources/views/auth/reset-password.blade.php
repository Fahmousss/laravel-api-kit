<x-layout-auth 
    title="Reset password" 
    description="Enter your new password below"
>
    <form method="POST" action="{{ route('password.reset') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-form.input 
            label="Email Address" 
            name="email" 
            type="email" 
            :value="old('email', $request->email)" 
            required 
            autofocus 
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

        <x-form.button label="Reset Password" />
    </form>
</x-layout-auth>
