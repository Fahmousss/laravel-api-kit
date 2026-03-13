@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-[calc(100vh-150px)] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-extrabold text-emerald-950">
            Reset your password
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-100">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <x-form.input name="email" label="Email address" type="email" :value="old('email', $email)" required autofocus />

                <x-form.input name="password" label="New Password" type="password" required />

                <x-form.input name="password_confirmation" label="Confirm New Password" type="password" required />

                <div>
                    <x-form.button class="w-full">
                        Reset Password
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
