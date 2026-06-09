<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('guest cannot access roles', function () {
    $response = $this->getJson('/api/admin/roles');

    $response->assertStatus(401);
});

test('unauthorized users cannot access roles', function () {
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 2
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/roles');

    $response->assertStatus(403);
});

test('authorized users can access roles', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/roles');

    $response->assertStatus(200);
});

test('authorized users can create roles', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/admin/roles', [
        'name' => 'moderator',
        'permissions' => [
             1, 2, 3, 4
        ]
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('roles', [
        'name' => 'moderator'
    ]);
});

test('authorized users can update roles', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $role = Role::create([
        'name' => 'manager'
    ]);
    $role->permissions()->sync([1]);
    $response = $this->putJson("/api/admin/roles/{$role->id}", [
        'name' => 'moderator',
        'permissions' => [
             1, 2, 3, 4
        ]
    ]);

    $response
    ->assertStatus(200)
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'users_count',
            'permissions' 
        ]
    ]);
    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'moderator'
    ]);
});

test('authorized users can delete roles', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $role = Role::create([
        'name' => 'moderator'
    ]);
    $role->permissions()->sync([1]);
    $response = $this->deleteJson("/api/admin/roles/{$role->id}");

    $response
    ->assertStatus(200)
    ->assertJson([
        'message' => 'Role deleted successfully.'
    ]);
    $this->assertDatabaseMissing('roles', [
        'id' => $role->id
    ]);
});

test('authorized users cannot delete roles with a user', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $role = Role::create([
        'name' => 'moderator'
    ]);
    User::factory()->create([
        'role_id' => $role->id
    ]);
    $role->permissions()->sync([1]);
    $response = $this->deleteJson("/api/admin/roles/{$role->id}");

    $response
    ->assertStatus(409);
});