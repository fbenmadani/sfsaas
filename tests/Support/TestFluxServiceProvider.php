<?php

namespace Tests\Support;

use Flux\FluxServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TestFluxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Replicate the component path booting from FluxServiceProvider
        if (file_exists(resource_path('views/flux'))) {
            Blade::anonymousComponentPath(resource_path('views/flux'), 'flux');
        }

        Blade::anonymousComponentPath(__DIR__.'/../../../vendor/livewire/flux/src/stubs/resources/views/flux', 'flux');
    }
}
