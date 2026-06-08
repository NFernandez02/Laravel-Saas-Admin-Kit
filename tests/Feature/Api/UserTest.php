<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('guest cannot access users', function () {
    $response = $this->getJson('/api/admin/users');

    $response->assertStatus(401);
});

test('unauthorized users cannot access users', function () {
    $this->seed();

    $user = User::factory()->create([
        'role_id' => 2
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/users');

    $response->assertStatus(403);
});

test('authorized users can access users', function(){
    $this->seed();

    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/users');

    $response->assertStatus(200);
});

test('authorized users can create a user', function(){
    $this->seed();

    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/admin/users', [
        'name' => 'user',
        'email' => 'user@example.com',
        'password' => Hash::make('password'),
        'role_id' => 2
    ]);
    $response
    ->assertCreated();
});

test('authorized users can update a user', function(){
    $this->seed();
    $admin = User::factory()->create([
        'role_id' => 1
    ]);
    $user = User::factory()->create([
        'role_id' => 2
    ]);
    Sanctum::actingAs($admin);
    $response = $this->putJson("/api/admin/users/{$user->id}", [
        'name' => 'example',
        'role_id' => 1
    ]);
    $response
    ->assertStatus(200)
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'email',
            
            'role' => [
                'id',
                'name'
            ]
        ]
    ]);
});

test('authorized users can delete a user', function(){
    $this->seed();
    $admin = User::factory()->create([
        'role_id' => 1
    ]);
    $user = User::factory()->create([
        'role_id' => 2
    ]);
    Sanctum::actingAs($admin);
    $response = $this->deleteJson("/api/admin/users/{$user->id}");
    $response
    ->assertStatus(200)
    ->assertJson([
        'message' => 'User deleted successfully.'
    ]);
});

test('authorized users cannot delete itself', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->deleteJson("/api/admin/users/{$user->id}");
    $response
    ->assertStatus(403);
});