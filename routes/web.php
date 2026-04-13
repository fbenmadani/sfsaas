<?php

use Illuminate\Support\Facades\Route;





foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
    
        
        Route::view('/', 'marketing.home')->name('home');
        Route::view('/features', 'marketing.features')->name('marketing.features');
        Route::view('/pricing', 'marketing.pricing')->name('marketing.pricing');
        Route::view('/about', 'marketing.about')->name('marketing.about');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::view('dashboard', 'dashboard')->name('dashboard');

            Route::middleware('admin')->group(function () {
                Route::get('admin/users', function () { return 'Users'; })->name('admin.users.index');
            });
        });


    });
}



require __DIR__.'/settings.php';
