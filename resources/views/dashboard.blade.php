@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-2xl mx-auto mt-16 text-center">
    <span class="inline-block bg-emerald-600/15 border border-indigo-500/30 text-emerald-700 rounded-full px-3.5 py-1 text-xs font-medium mb-6">
        {{ $user->primaryRole }}
    </span>
    
    <h1 class="text-3xl font-bold mb-3 text-emerald-950">Hello, {{ $user->displayName }}</h1>
    
    <p class="text-slate-500 mb-1.5">Signed in as <strong class="text-slate-800 font-medium">{{ $user->email }}</strong></p>
    <p class="text-slate-500 mb-4">Member since {{ $user->memberSince }}</p>
    
    @unless($user->isEmailVerified)
        <p class="mt-4 text-xs text-rose-400 flex items-center justify-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
              <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>
            Email not yet verified.
        </p>
    @endunless
</div>
@endsection

