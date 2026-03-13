
<x-layout-auth 
    title="Verify email" 
    description="Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?"
>
    @if (session('status') == 'verification-link-sent')
        <x-ui.alerts variant="success" class="mb-4">
            A new verification link has been sent to the email address you provided during registration.
        </x-ui.alerts>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-form.button label="Resend Verification Email" />
        </form>

        <form method="POST" action="{{ route('logout') }}" class="flex justify-center">
            @csrf
            <x-ui.button type="submit" variant="danger" >Log Out</x-ui.button>
        </form>
    </div>
</x-layout-auth>
