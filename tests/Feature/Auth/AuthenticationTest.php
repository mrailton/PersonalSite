<?php

use App\Models\User;

test('login form renders', function () {
    guest();

    $res = $this->get(route('login.create'));

    $res->assertSee('Mark Railton')
        ->assertSee('Sign in to your account')
        ->assertSee('Sign in');
});

test('a registered user can login', function () {
    guest();

    $user = User::factory()->create();

    $res = $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password']);

    $res->assertRedirectToRoute('admin.dashboard')
        ->assertSessionDoesntHaveErrors();
    $this->assertAuthenticated();
});

test('a guest can not login if not registered', function () {
    guest();

    $res = $this->post(route('login.store'), ['email' => 'no@user.com', 'password' => 'password']);

    $res->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('an authenticated user can not access the login page', function () {
    authenticatedUser();

    $res = $this->get(route('login.create'));

    $res->assertRedirectToRoute('admin.dashboard');
});
