<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can visit the login page', function () {
    $page = visit('/login');

    $page->assertSee('Log in')
        ->assertNoJavaScriptErrors();
});

it('can authenticate a user', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => Hash::make('password'),
    ]);

    visit('/login')
        ->fill('email', 'jane@example.com')
        ->fill('password', 'password')
        ->press('Log in')
        ->wait(1)
        ->assertPathIs('/dashboard')
        ->assertNoJavaScriptErrors();
});

it('shows validation errors with invalid credentials', function () {
    visit('/login')
        ->fill('email', 'wrong@example.com')
        ->fill('password', 'wrong-password')
        ->press('Log in')
        ->assertSee('email')
        ->assertNoJavaScriptErrors();
});

it('can visit the register page', function () {
    $page = visit('/register');

    $page->assertSee('Create an account')
        ->assertNoJavaScriptErrors();
});

it('can register a new user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password')
        ->fill('password_confirmation', 'password')
        ->press('Create account')
        ->assertPathIs('/dashboard')
        ->assertNoJavaScriptErrors();
});
