@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="card w-full max-w-lg bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h1 class="card-title text-2xl font-bold mb-1 text-base-content">Verify your email</h1>
            <p class="text-base-content/60 text-sm mb-6">
                Thanks for signing up! Please check your email and click the verification link. 
                If you didn't receive it, we'll gladly send another.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div role="alert" class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-sm">A new verification link has been sent to your email address.</span>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-4">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <x-form.button label="Resend Verification Email" />
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm w-full sm:w-auto">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
