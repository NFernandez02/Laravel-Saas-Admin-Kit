<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('User can log in with the right credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role_id' => 1
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password'
    ]);


    $response
    ->assertStatus(200)
    ->assertJsonStructure([
        'token'
    ]);
});

test('User cannot login with invalid password', function(){
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role_id' => 1
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'incorrect-password'
    ]);

    $response
    ->assertStatus(401)
    ->assertJson([
        'message' => 'Invalid Credentials'
    ]);
});

test('login requires email', function(){

    $response = $this->postJson('/api/login', [
        'password' => 'password'
    ]);

    $response
    ->assertStatus(422)
    ->assertJsonValidationErrors('email');
});

test('login requires password', function(){

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com'
    ]);

    $response
    ->assertStatus(422)
    ->assertJsonValidationErrors('password');
});


test('guest cannot logout', function(){
    $response = $this->postJson('/api/logout');

    $response
    ->assertStatus(401);
});

test('user can logout', function(){
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response
    ->assertStatus(200)
    ->assertJson([
        'message' => 'Logged out successfully.'
    ]);
});