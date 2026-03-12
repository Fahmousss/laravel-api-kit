@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white border border-slate-200 rounded-xl p-10 w-full max-w-md backdrop-blur-sm shadow-2xl">
        <h1 class="text-2xl font-bold mb-1.5 text-emerald-950">Welcome back</h1>
        <p class="text-slate-500 text-sm mb-8">Sign in to your account</p>

        <form method="POST" action="{{ route('web.login.store') }}" class="space-y-5">
            @csrf

            <x-form.input
                name="email"
                label="Email"
                type="email"
                autocomplete="email"
                autofocus
            />

            <x-form.input
                name="password"
                label="Password"
                type="password"
                autocomplete="current-password"
            />

            <div class="pt-2">
                <x-form.button label="Sign in" />
            </div>
        </form>
    </div>
</div>
@endsection

