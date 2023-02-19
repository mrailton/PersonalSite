<?php

test('an authenticated user can logout', function () {
    authenticatedUser();

    $this->assertAuthenticated();
    $res = $this->post(route('auth.logout'));

    $res->assertRedirectToRoute('index')
        ->assertSessionDoesntHaveErrors();

    $this->assertGuest();
});
