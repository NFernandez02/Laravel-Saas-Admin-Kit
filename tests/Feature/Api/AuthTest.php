<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('User can log in with the right credentials', function () {
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role_id' => $adminRole->id,
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'token',
        ]);
});

test('User cannot login with invalid password', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role_id' => $adminRole->id,
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'test@example.com',
        'password' => 'incorrect-password',
    ]);

    $response
        ->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid Credentials',
        ]);
});

test('login requires email', function () {

    $response = $this->postJson('/api/v1/login', [
        'password' => 'password',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('email');
});

test('login requires password', function () {

    $response = $this->postJson('/api/v1/login', [
        'email' => 'test@example.com',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password');
});

test('guest cannot logout', function () {
    $response = $this->postJson('/api/v1/logout');

    $response
        ->assertStatus(401);
});

test('user can logout', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/logout');

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully.',
        ]);
});

test('login rate is limited', function () {
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    User::factory()->create([
        'email' => 'admin@test.com',
        'password' => 'password',
        'role_id' => $adminRole->id,
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/v1/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);
    }

    $response = $this->postJson('/api/v1/login', [
        'email' => 'admin@test.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(429);
});
