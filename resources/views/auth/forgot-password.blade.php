@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h1 class="card-title text-2xl font-bold mb-1 text-base-content">Forgot password?</h1>
            <p class="text-base-content/60 text-sm mb-6">
                No problem. We'll send you a link to reset it.
            </p>

            @if (session('status'))
                <div role="alert" class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-sm">{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <x-form.input 
                    name="email" 
                    label="Email address" 
                    type="email" 
                    :value="old('email')" 
                    placeholder="you@example.com"
                    required 
                    autofocus 
                />

                <div class="card-actions justify-end mt-4">
                    <x-form.button label="Email Reset Link" />
                </div>
            </form>

            <div class="divider mt-8"></div>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="link link-hover text-sm text-base-content/60">Back to sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection
