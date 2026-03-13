@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4 py-12">
    <div class="card w-full max-w-md bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h1 class="card-title text-2xl font-bold mb-1 text-base-content">Create account</h1>
            <p class="text-base-content/60 text-sm mb-6">Join our Onion Architecture starter</p>

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <x-form.input 
                    name="name" 
                    label="Full Name" 
                    type="text" 
                    placeholder="John Doe" 
                    required 
                    autofocus 
                />
                
                <x-form.input 
                    name="email" 
                    label="Email address" 
                    type="email" 
                    placeholder="john@example.com" 
                    required 
                />

                <x-form.input 
                    name="password" 
                    label="Password" 
                    type="password" 
                    placeholder="••••••••"
                    required 
                />

                <x-form.input 
                    name="password_confirmation" 
                    label="Confirm Password" 
                    type="password" 
                    placeholder="••••••••"
                    required 
                />

                <div class="card-actions justify-end mt-4">
                    <x-form.button label="Register" />
                </div>
            </form>

            <div class="divider text-xs text-base-content/40 mt-8">OR</div>
            
            <div class="text-center text-sm">
                <span class="text-base-content/60">Already have an account?</span>
                <a href="{{ route('login') }}" class="link link-primary font-semibold">Sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection
