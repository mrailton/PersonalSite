<?php

use App\Models\User;

test('user can authenticate with correct credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['access_token']);
});

test('user can not authenticate with incorrect credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password'
    ]);

    $response->assertStatus(422)
        ->assertJsonMissing(['access_token']);
});
