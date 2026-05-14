<?php

use App\Models\Feature;
use Tests\TestCase;

uses(TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

it('has the correct fillable properties', function () {
    $feature = new Feature;

    expect($feature->getFillable())->toBe(['name', 'slug', 'type']);
});

test('feature model has type attribute', function () {
    $feature = Feature::create([
        'name' => 'Test Feature',
        'slug' => 'test-feature',
        'type' => 'limit',
    ]);

    expect($feature->type)->toBe('limit');
});
