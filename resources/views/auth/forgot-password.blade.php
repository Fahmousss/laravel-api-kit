@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[calc(100vh-150px)] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-extrabold text-emerald-950">
            Forgot your password?
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>

        @if (session('status'))
            <div class="mt-4 font-medium text-sm text-emerald-600 text-center">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-100">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <x-form.input name="email" label="Email address" type="email" :value="old('email')" required autofocus />

                <div>
                    <x-form.button class="w-full">
                        Email Password Reset Link
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
