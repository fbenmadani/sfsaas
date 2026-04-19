<?php

test('home page returns a successful response', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200);
    $response->assertSee('Grow your business');
});

test('features page returns a successful response', function () {
    $response = $this->get(route('marketing.features'));
    $response->assertStatus(200);
    $response->assertSee('Tools, Simply Built');
});

test('pricing page returns a successful response', function () {
    $response = $this->get(route('marketing.pricing'));
    $response->assertStatus(200);
    $response->assertSee('Simple, Cheerful Pricing');
});

test('about page returns a successful response', function () {
    $response = $this->get(route('marketing.about'));
    $response->assertStatus(200);
    $response->assertSee('Mission to Empower SMBs');
});
