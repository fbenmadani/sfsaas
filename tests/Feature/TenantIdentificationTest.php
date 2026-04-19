<?php

use function Pest\Laravel\get;

it('shows the custom 404 tenant page when tenant cannot be identified by subdomain', function () {
    // We use a subdomain of a central domain that doesn't exist as a tenant
    $response = get('http://unknown.sfsaas.test');

    $response->assertStatus(404);
    $response->assertSee('Account Not Found');
    // $response->assertSee('Oops! We couldn\'t find the workspace you\'re looking for.', false);
});

it('shows the custom 404 tenant page when tenant cannot be identified by domain', function () {
    // We use a domain that is not a central domain and doesn't exist as a tenant
    $response = get('http://completely-unknown-domain.test');

    $response->assertStatus(404);
    $response->assertSee('Workspace Not Found');
});
