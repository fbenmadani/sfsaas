<?php

use App\Http\Livewire\Admin\Features\FeatureManager;
use App\Http\Livewire\Admin\Plans\PlanManager;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Security;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');

    // Temporary routes for Plan and Feature Management
    // Route::livewire('admin/plans', PlanManager::class)->name('admin.plans');
    //  Route::livewire('admin/features', FeatureManager::class)->name('admin.features');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::livewire('settings/security', Security::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('security.edit');
});
