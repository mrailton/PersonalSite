<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    public function authenticate(?User $user = null): void
    {
        if (! $user) {
            $user = User::factory()->create();
        }

        $this->actingAs($user);
    }
}
