<?php

test('an authenticated user can access the dashboard', function () {
    authenticatedUser();

    $res = $this->get(route('admin.dashboard'));

    $res->assertSee('Dashboard')
        ->assertSee('Articles')
        ->assertSee('Certificates')
        ->assertSee('Logout');
});

test('a guest can not access the admin dashboard', function () {
    guest();

    $res = $this->get(route('admin.dashboard'));

    $res->assertRedirectToRoute('login.create');
    $this->assertGuest();
});
