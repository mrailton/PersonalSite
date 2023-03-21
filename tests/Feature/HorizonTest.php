<?php

test('a guest can not access horizon in production mode', function () {
    app()->environment('production');

    guest()->get(route('horizon.index'))
        ->assertStatus(403);
});

test('an authenticated user can access horizon in production mode', function () {
    app()->environment('production');

    authenticatedUser()->get(route('horizon.index'))
        ->assertStatus(200);
});

test('a specified bearer token can be used to access horizon', function () {
    app()->environment('production');

    guest()->withHeader('Authorization', 'Bearer token123')
        ->get(route('horizon.index'))
        ->assertStatus(200);
});
