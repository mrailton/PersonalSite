<?php

declare(strict_types=1);

use App\Models\Certificate;

test('a user can delete a certificate', function () {
    $certificate = Certificate::factory()->create();

    authenticatedUser()->delete(route('admin.certificates.delete', [$certificate]))
        ->assertSessionDoesntHaveErrors()
        ->assertRedirectToRoute('admin.certificates.list');

    $this->assertSoftDeleted($certificate);
});
