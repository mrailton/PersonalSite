<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function an_authenticated_user_can_access_the_dashboard(): void
    {
        $this->actingAs(User::factory()->create());

        $res = $this->get(route('admin.dashboard'));

        $res->assertSee('Dashboard')
            ->assertSee('Articles')
            ->assertSee('Logout');
    }

    /** @test */
    public function a_guest_can_not_access_the_admin_dashboard(): void
    {
        $res = $this->get(route('admin.dashboard'));

        $res->assertRedirectToRoute('login.create');
        $this->assertGuest();
    }
}
