<?php

use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::view('/', 'marketing.home')->name('home');
        Route::view('/features', 'marketing.features')->name('marketing.features');
        Route::view('/pricing', 'marketing.pricing')->name('marketing.pricing');
        Route::view('/about', 'marketing.about')->name('marketing.about');
        Route::livewire('/sign-up', 'pages::account.sign-up')->name('account.sign-up');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::livewire('dashboard', 'dashboard')->name('dashboard');
            Route::livewire('/users', 'users.index')->name('users.index');
            Route::middleware('admin')->group(function () {
                Route::livewire('admin/users', 'admin.users.index')->name('admin.users.index');
                Route::livewire('admin/tenants', 'admin.tenants.index')->name('admin.tenants.index');
                Route::livewire('admin/tenants/{tenant}', 'admin.tenants.show')->name('admin.tenants.show');
                Route::get('test-tenant/{tenant}', function ($tenant) {
                    dd('Hit test route!', $tenant);
                });
                Route::livewire('admin/features', 'admin.features.index')->name('admin.features.index');
                Route::livewire('admin/plans', 'admin.plans.index')->name('admin.plans.index');
                Route::livewire('admin/domains', 'admin.domains.index')->name('admin.domains.index');
                Route::livewire('admin/domains/create', 'admin.domains.create')->name('admin.domains.create');
                Route::livewire('admin/domains/{domain}/edit', 'admin.domains.edit')->name('admin.domains.edit');
            });
        });

    });
}

require __DIR__.'/settings.php';
