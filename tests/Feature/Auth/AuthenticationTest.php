<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /** @test */
    public function login_form_renders(): void
    {
        $res = $this->get(route('login.create'));

        $res->assertSee('Mark Railton')
            ->assertSee('Sign in to your account')
            ->assertSee('Sign in');
    }

    /** @test */
    public function a_registered_user_can_login(): void
    {
        $user = User::factory()->create();

        $res = $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password']);

        $res->assertRedirectToRoute('admin.dashboard')
            ->assertSessionDoesntHaveErrors();
        $this->assertAuthenticated();
    }

    /** @test */
    public function a_guest_can_not_login_if_not_registered(): void
    {
        $res = $this->post(route('login.store'), ['email' => 'no@user.com', 'password' => 'password']);

        $res->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function an_authenticated_user_can_not_access_the_login_page(): void
    {
        $this->actingAs(User::factory()->create());

        $res = $this->get(route('login.create'));

        $res->assertRedirectToRoute('admin.dashboard');
    }
}
