<?php

declare(strict_types=1);

use App\Models\Certificate;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('a user can add a new certificate', function () {
    authenticatedUser();
    $data = [
        'name' => 'Test Certificate',
        'issued_by' => 'Test Institute',
        'issued_on' => now()->subYear(1)->format('Y-m-d'),
        'expires_on' => now()->addYear(1)->format('Y-m-d'),
    ];

    expect(Certificate::count())->toBe(0);

    get(route('admin.certificates.list'))
        ->assertSee('Add Certificate');

    get(route('admin.certificates.create'))
        ->assertSee('Name:');

    post(route('admin.certificates.store'), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.certificates.list');

    expect(Certificate::count())->toBe(1);

    get(route('admin.certificates.list'))
        ->assertSee($data['name'])
        ->assertSee($data['issued_by']);
});
