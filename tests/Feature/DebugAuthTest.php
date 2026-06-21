<?php

use App\Models\User;

test('debug login 419', function () {
    echo "Running unit tests: " . (app()->runningUnitTests() ? 'yes' : 'no') . "\n";
    echo "App Env: " . app()->environment() . "\n";
    echo "Unit Testing Config: " . (config('app.unit_testing') ? 'yes' : 'no') . "\n";
    $this->withoutExceptionHandling();
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    echo "Status: " . $response->status() . "\n";
    if ($response->status() == 419) {
        echo "CSRF issue detected\n";
    }
    
    $response->assertRedirect();
});
