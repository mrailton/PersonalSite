<?php

use App\Models\User;

test('login form renders', function () {
    guest()->get(route('login.create'))
        ->assertSee('Mark Railton')
        ->assertSee('Sign in to your account')
        ->assertSee('Sign in');
});

test('a registered user can login', function () {
    $user = User::factory()->create();

    guest()->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirectToRoute('admin.dashboard')
        ->assertSessionDoesntHaveErrors();

    $this->assertAuthenticated();
});

test('a guest can not login if not registered', function () {
    guest()->post(route('login.store'), ['email' => 'no@user.com', 'password' => 'password'])
        ->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

test('an authenticated user can not access the login page', function () {
    authenticatedUser()->get(route('login.create'))
        ->assertRedirectToRoute('admin.dashboard');
});

test('an authenticated user can logout', function () {
    authenticatedUser();

    $this->assertAuthenticated();

    authenticatedUser()->post(route('auth.logout'))
        ->assertRedirectToRoute('index')
        ->assertSessionDoesntHaveErrors();

    $this->assertGuest();
});
