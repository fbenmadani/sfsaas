<?php

use App\Models\Feature;

it('has the correct fillable properties', function () {
    $feature = new Feature;

    expect($feature->getFillable())->toBe(['name', 'slug', 'type']);
});
