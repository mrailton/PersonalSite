<?php

declare(strict_types=1);

use App\Models\Certificate;

test('a user can view a certificate', function () {
    $certificate = Certificate::factory()->create();

    authenticatedUser()->get(route('admin.certificates.show', ['certificate' => $certificate]))
        ->assertSee($certificate->name)
        ->assertSee($certificate->issued_by)
        ->assertSee($certificate->issued_on->format('jS M Y'))
        ->assertSee($certificate->expires_on->format('jS M Y'));
});
