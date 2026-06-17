<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('guest cannot access users', function () {
    $response = $this->getJson('/api/admin/users');

    $response->assertStatus(401);
});

test('unauthorized users cannot access users', function () {

    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/users');

    $response->assertStatus(403);
});

test('authorized users can access users', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/users');

    $response->assertStatus(200);
});

test('authorized users can create a user', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/admin/users', [
        'name' => 'user',
        'email' => 'user@example.com',
        'password' => 'password',
        'role_id' => 2,
    ]);
    $response
        ->assertCreated();

    $this->assertDatabaseHas('users', [
        'email' => 'user@example.com',
    ]);
});

test('authorized users can update a user', function () {
    $userRole = Role::where('name', 'user')->firstOrFail();
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $admin = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    $user = User::factory()->create([
        'name' => 'example',
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($admin);
    $response = $this->putJson("/api/admin/users/{$user->id}", [
        'name' => 'user',
        'role_id' => 1,
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
                    'name',
                ],
            ],
        ]);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'user',
    ]);
});

test('authorized users can delete a user', function () {

    $userRole = Role::where('name', 'user')->firstOrFail();
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $admin = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    $user = User::factory()->create([
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($admin);
    $response = $this->deleteJson("/api/admin/users/{$user->id}");
    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'User deleted successfully.',
        ]);
    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

test('authorized users cannot delete itself', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->deleteJson("/api/admin/users/{$user->id}");
    $response
        ->assertStatus(403);

});
