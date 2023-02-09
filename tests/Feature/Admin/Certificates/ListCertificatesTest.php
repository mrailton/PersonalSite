<?php

declare(strict_types=1);

use App\Models\Certificate;
use function Pest\Laravel\get;

test('a user can view a list of earned certificates', function () {
    authenticatedUser();
    $certificates = Certificate::factory()->count(5)->create();

    get(route('admin.certificates.list'))
        ->assertStatus(200)
        ->assertSee($certificates[0]->name)
        ->assertSee($certificates[2]->name);
});
