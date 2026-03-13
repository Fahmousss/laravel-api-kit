@extends('layouts.app')

@section('title', 'Laravel Onion Starter - Clean DDD & CQRS Architecture')

@section('content')
<div class="min-h-screen bg-base-200">
    <!-- Hero Section -->
    <div class="hero min-h-[70vh] bg-base-100 border-b border-base-300">
        <div class="hero-content text-center">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-extrabold tracking-tight sm:text-6xl lg:text-7xl">
                    Laravel <span class="bg-clip-text text-transparent bg-linear-to-r from-primary to-secondary">Onion Starter</span>
                </h1>
                <p class="py-8 text-lg text-base-content/70 leading-8">
                    Empower your development with a rigorously structured starter kit. 
                    Built with <strong>Domain-Driven Design</strong>, <strong>CQRS</strong>, and <strong>Onion Architecture</strong> for maximum scalability and maintainability.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg shadow-lg">
                        Get Started
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="https://github.com/Fahmousss/laravel-api-kit" class="btn btn-outline btn-lg">
                        View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Architecture Breakdown -->
    <div class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm">Architecture</h2>
            <p class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-base-content">
                Engineered for Clean Code
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Domain Layer -->
            <div class="card bg-base-100 shadow-sm border border-base-300 hover:shadow-md transition-shadow">
                <div class="card-body">
                    <div class="w-12 h-12 bg-success/10 text-success rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                        1
                    </div>
                    <h3 class="card-title text-base-content">Domain Layer</h3>
                    <p class="text-sm text-base-content/70">
                        Absolute core business logic. Pure PHP with zero framework dependencies. Entities, Enums, and Repository Interfaces.
                    </p>
                </div>
            </div>

            <!-- Application Layer -->
            <div class="card bg-base-100 shadow-sm border border-base-300 hover:shadow-md transition-shadow">
                <div class="card-body">
                    <div class="w-12 h-12 bg-info/10 text-info rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                        2
                    </div>
                    <h3 class="card-title text-base-content">Application Layer</h3>
                    <p class="text-sm text-base-content/70">
                        Use-case orchestration via Commands and Queries. Decoupled handlers that translate domain logic into real-world actions.
                    </p>
                </div>
            </div>

            <!-- Infrastructure Layer -->
            <div class="card bg-base-100 shadow-sm border border-base-300 hover:shadow-md transition-shadow">
                <div class="card-body">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                        3
                    </div>
                    <h3 class="card-title text-base-content">Infrastructure</h3>
                    <p class="text-sm text-base-content/70">
                        The framework layer. Eloquent models, migrations, service implementations, and external integrations live here.
                    </p>
                </div>
            </div>

            <!-- Presentation Layer -->
            <div class="card bg-base-100 shadow-sm border border-base-300 hover:shadow-md transition-shadow">
                <div class="card-body">
                    <div class="w-12 h-12 bg-warning/10 text-warning rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                        4
                    </div>
                    <h3 class="card-title text-base-content">Presentation</h3>
                    <p class="text-sm text-base-content/70">
                        Bus-only Controllers and ViewComposers. Decoupled from logic, focusing purely on dispatching and UI transformation.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="bg-base-100 py-24 border-t border-base-300">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-base-content">Ready to build something great?</h2>
            <p class="mt-4 text-lg text-base-content/60">
                Kickstart your next enterprise Laravel application with the structure it deserves.
            </p>
            <div class="mt-10">
                <a href="{{ route('register') }}" class="btn btn-primary btn-wide btn-lg shadow-lg">
                    Create your account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
