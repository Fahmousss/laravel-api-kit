@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[calc(100vh-150px)] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-3xl font-extrabold text-emerald-950">
            Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Or
            <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-500">
                sign in to your existing account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-100">
            <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
                @csrf

                <x-form.input name="name" label="Full Name" type="text" placeholder="John Doe" required autofocus />
                
                <x-form.input name="email" label="Email address" type="email" placeholder="john@example.com" required />

                <x-form.input name="password" label="Password" type="password" required />

                <x-form.input name="password_confirmation" label="Confirm Password" type="password" required />

                <div>
                    <x-form.button class="w-full">
                        Register
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
