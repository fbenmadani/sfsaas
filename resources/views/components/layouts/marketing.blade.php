<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'sfSaas') }} - {{ $title ?? 'Scalable SMB Solutions' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-white font-sans antialiased text-zinc-900">
        <header class="bg-white border-b border-zinc-100">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <flux:navbar class="flex-1 py-4">
                    <flux:brand href="/" name="sfSaas" class="text-brand-secondary" />

                    <flux:spacer />

                    <flux:navbar.item href="/" :current="request()->is('/')">Home</flux:navbar.item>
                    <flux:navbar.item href="/features" :current="request()->is('features')">Features</flux:navbar.item>
                    <flux:navbar.item href="/pricing" :current="request()->is('pricing')">Pricing</flux:navbar.item>
                    <flux:navbar.item href="/about" :current="request()->is('about')">About</flux:navbar.item>

                    <flux:separator vertical variant="subtle" class="mx-2" />

                    <flux:button href="/login" variant="ghost" size="sm">Log in</flux:button>
                    <flux:button href="/register" variant="primary" size="sm" class="bg-brand-secondary">Get Started</flux:button>
                </flux:navbar>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="bg-zinc-50 border-t border-zinc-100 py-12">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                    <flux:brand href="/" name="sfSaas" class="text-brand-secondary" />
                    <p class="text-sm text-zinc-500">
                        &copy; {{ date('Y') }} sfSaas. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
