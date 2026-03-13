@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h1 class="card-title text-2xl font-bold mb-1 text-base-content">Reset password</h1>
            <p class="text-base-content/60 text-sm mb-6">Choose a secure new password</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <x-form.input 
                    name="email" 
                    label="Email address" 
                    type="email" 
                    :value="old('email', $email)" 
                    required 
                    autofocus 
                />

                <x-form.input 
                    name="password" 
                    label="New Password" 
                    type="password" 
                    placeholder="••••••••"
                    required 
                />

                <x-form.input 
                    name="password_confirmation" 
                    label="Confirm New Password" 
                    type="password" 
                    placeholder="••••••••"
                    required 
                />

                <div class="card-actions justify-end mt-4">
                    <x-form.button label="Reset Password" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
