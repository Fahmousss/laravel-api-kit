@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h1 class="card-title text-2xl font-bold mb-1 text-base-content">Welcome back</h1>
            <p class="text-base-content/60 text-sm mb-6">Sign in to your account</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <x-form.input
                    name="email"
                    label="Email"
                    type="email"
                    autocomplete="email"
                    autofocus
                    placeholder="you@example.com"
                />

                <x-form.input
                    name="password"
                    label="Password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <div class="card-actions justify-end mt-4">
                    <x-form.button label="Sign in" />
                </div>
            </form>

            <div class="divider text-xs text-base-content/40 mt-8">OR</div>
            
            <div class="text-center text-sm">
                <span class="text-base-content/60">New here?</span>
                <a href="{{ route('register') }}" class="link link-primary font-semibold">Create account</a>
            </div>
            
            <div class="text-center mt-2">
                <a href="{{ route('password.request') }}" class="link link-hover text-xs text-base-content/50">Forgot your password?</a>
            </div>
        </div>
    </div>
</div>
@endsection
