@extends('layouts.app')

@section('title', 'Laravel Onion Starter - Clean DDD & CQRS Architecture')

@section('content')
<div class="relative overflow-hidden bg-slate-50 min-h-screen">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] pointer-events-none overflow-hidden opacity-10">
        <div class="absolute top-[-100px] left-[-100px] w-[500px] h-[500px] rounded-full bg-emerald-400 blur-[100px]"></div>
        <div class="absolute top-[200px] right-[-100px] w-[400px] h-[400px] rounded-full bg-blue-400 blur-[100px]"></div>
    </div>

    <!-- Hero Section -->
    <div class="relative pt-20 pb-16 px-4 sm:px-6 lg:pt-32 lg:pb-24 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl font-extrabold tracking-tight text-emerald-950 sm:text-6xl lg:text-7xl">
                Laravel <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-emerald-400">Onion Starter</span>
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-lg text-slate-600 leading-8">
                Empower your development with a rigorously structured starter kit. 
                Built with <strong>Domain-Driven Design</strong>, <strong>CQRS</strong>, and <strong>Onion Architecture</strong> for maximum scalability and maintainability.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-emerald-600 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-lg shadow-emerald-200">
                    Get Started
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="https://github.com/Fahmousss/laravel-api-kit" class="inline-flex items-center justify-center px-8 py-4 font-bold text-emerald-950 transition-all duration-200 bg-white border-2 border-slate-100 rounded-xl hover:bg-slate-50 hover:border-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-100 active:scale-95 shadow-sm">
                    View on GitHub
                </a>
            </div>
        </div>
    </div>

    <!-- Architecture Breakdown -->
    <div class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center mb-16">
                <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Architecture</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-emerald-950 sm:text-4xl">
                    Engineered for Clean Code
                </p>
            </div>

            <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Domain Layer -->
                <div class="relative p-8 bg-white/60 backdrop-blur-xl border border-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-950 mb-3">Domain Layer</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        Absolute core business logic. Pure PHP with zero framework dependencies. Entities, Enums, and Repository Interfaces.
                    </p>
                </div>

                <!-- Application Layer -->
                <div class="relative p-8 bg-white/60 backdrop-blur-xl border border-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-950 mb-3">Application Layer</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        Use-case orchestration via Commands and Queries. Decoupled handlers that translate domain logic into real-world actions.
                    </p>
                </div>

                <!-- Infrastructure Layer -->
                <div class="relative p-8 bg-white/60 backdrop-blur-xl border border-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-950 mb-3">Infrastructure</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        The framework layer. Eloquent models, migrations, service implementations, and external integrations live here.
                    </p>
                </div>

                <!-- Presentation Layer -->
                <div class="relative p-8 bg-white/60 backdrop-blur-xl border border-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-950 mb-3">Presentation</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">
                        Bus-only Controllers and ViewComposers. Decoupled from logic, focusing purely on dispatching and UI transformation.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="py-24 border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-emerald-950">Ready to build something great?</h2>
            <p class="mt-4 text-lg text-slate-500">
                Kickstart your next enterprise Laravel application with the structure it deserves.
            </p>
            <div class="mt-10 flex justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-bold text-emerald-600 transition-colors duration-200 bg-white border-2 border-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white">
                    Create your account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
