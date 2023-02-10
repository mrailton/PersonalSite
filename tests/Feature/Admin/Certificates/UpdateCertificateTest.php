<?php

declare(strict_types=1);

use App\Models\Certificate;

test('a user can update a certificate', function () {
    $certificate = Certificate::factory()->create();
    $data = $certificate->toArray();
    $data['name'] = 'Updated Name';

    authenticatedUser()->get(route('admin.certificates.edit', [$certificate]))
        ->assertSee($certificate->name)
        ->assertSee('Submit');

    authenticatedUser()->put(route('admin.certificates.update', [$certificate]), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.certificates.list');

    authenticatedUser()->get(route('admin.certificates.show', [$certificate]))
        ->assertSee('Updated Name');
});
