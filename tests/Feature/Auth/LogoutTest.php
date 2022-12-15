<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /** @test */
    public function an_authenticated_user_can_logout(): void
    {
        $this->actingAs(User::factory()->create());

        $this->assertAuthenticated();
        $res = $this->post(route('auth.logout'));

        $res->assertRedirectToRoute('index')
            ->assertSessionDoesntHaveErrors();

        $this->assertGuest();
    }
}
