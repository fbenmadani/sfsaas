<?php

use App\Livewire\Admin\Features\Index;
use App\Livewire\Admin\Plans\Edit;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::view('/', 'marketing.home')->name('home');
        Route::view('/features', 'marketing.features')->name('marketing.features');
        Route::view('/pricing', 'marketing.pricing')->name('marketing.pricing');
        Route::view('/about', 'marketing.about')->name('marketing.about');
        Route::livewire('/sign-up', 'pages::account.sign-up')->name('account.sign-up');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::view('dashboard', 'dashboard')->name('dashboard');
            Route::livewire('/users', 'users.index')->name('users.index');
            Route::middleware('admin')->group(function () {
                Route::livewire('admin/users', 'admin.users.index')->name('admin.users.index');
                Route::livewire('admin/tenants', 'admin.tenants.index')->name('admin.tenants.index');
                Route::get('admin/features', Index::class)->name('admin.features.index');
                Route::get('admin/plans', App\Livewire\Admin\Plans\Index::class)->name('admin.plans.index');
                Route::get('admin/plans/{plan}/edit', Edit::class)->name('admin.plans.edit');
            });
        });

    });
}

require __DIR__.'/settings.php';
