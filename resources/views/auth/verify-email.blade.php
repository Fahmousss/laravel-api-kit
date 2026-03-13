@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-[calc(100vh-150px)] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-extrabold text-emerald-950">
            Verify your email
        </h2>
        <p class="mt-4 text-center text-sm text-slate-600">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 font-medium text-sm text-emerald-600 text-center">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-form.button>
                    Resend Verification Email
                </x-form.button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-slate-600 hover:text-slate-900">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
