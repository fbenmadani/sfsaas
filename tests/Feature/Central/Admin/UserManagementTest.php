<?php

use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('cannot be accessed by guests', function () {
    get('http://sfsaas.test/admin/users')
        ->assertRedirect('/login');
});

it('cannot be accessed by non-admin users', function () {
    $user = User::factory()->create(['is_admin' => false]);

    actingAs($user)
        ->get('http://sfsaas.test/admin/users')
        ->assertStatus(403);
});

it('can be accessed by admin users', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    actingAs($admin)
        ->get('http://sfsaas.test/admin/users')
        ->assertStatus(200)
        ->assertSee('Users');
});

it('lists all users in the index', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $users = User::factory()->count(3)->create(['is_admin' => false]);

    actingAs($admin)
        ->get('http://sfsaas.test/admin/users')
        ->assertStatus(200)
        ->assertSee($users[0]->name)
        ->assertSee($users[1]->name)
        ->assertSee($users[2]->name);
});
