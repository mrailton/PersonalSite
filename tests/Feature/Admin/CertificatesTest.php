<?php

use App\Models\Certificate;

test('a user can view a list of earned certificates', function () {
    $certificates = Certificate::factory()->count(5)->create();

    authenticatedUser()->get(route('admin.certificates.list'))
        ->assertStatus(200)
        ->assertSee($certificates[0]->name)
        ->assertSee($certificates[2]->name);
});

test('a user can add a new certificate', function () {
    $data = [
        'name' => 'Test Certificate',
        'issued_by' => 'Test Institute',
        'issued_on' => now()->subYear(1)->format('Y-m-d'),
        'expires_on' => now()->addYear(1)->format('Y-m-d'),
    ];

    expect(Certificate::count())->toBe(0);

    authenticatedUser()->get(route('admin.certificates.list'))
        ->assertSee('Add Certificate');

    authenticatedUser()->get(route('admin.certificates.create'))
        ->assertSee('Name:');

    authenticatedUser()->post(route('admin.certificates.store'), $data)
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.certificates.list');

    expect(Certificate::count())->toBe(1);

    authenticatedUser()->get(route('admin.certificates.list'))
        ->assertSee($data['name'])
        ->assertSee($data['issued_by']);
});

test('a user can view a certificate', function () {
    $certificate = Certificate::factory()->create();

    authenticatedUser()->get(route('admin.certificates.show', ['certificate' => $certificate]))
        ->assertSee($certificate->name)
        ->assertSee($certificate->issued_by)
        ->assertSee($certificate->issued_on->format('jS M Y'))
        ->assertSee($certificate->expires_on->format('jS M Y'));
});

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

test('a user can delete a certificate', function () {
    $certificate = Certificate::factory()->create();

    authenticatedUser()->delete(route('admin.certificates.delete', [$certificate]))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.certificates.list');

    $this->assertSoftDeleted($certificate);
});

