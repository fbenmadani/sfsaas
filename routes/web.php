<?php

use Illuminate\Support\Facades\Route;





foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
    
        
        Route::view('/', 'welcome')->name('home');

        Route::middleware(['auth', 'verified'])->group(function () {
            Route::view('dashboard', 'dashboard')->name('dashboard');
        });


    });
}



require __DIR__.'/settings.php';
