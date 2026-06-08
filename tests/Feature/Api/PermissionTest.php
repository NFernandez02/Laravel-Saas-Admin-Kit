<?php

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('guest cannot access permissions', function () {
    $response = $this->getJson('/api/admin/permissions');

    $response->assertStatus(401);
});

test('unauthorized user cannot access permissions', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 2
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/permissions');
    $response->assertStatus(403);
});

test('authorized user can access permissions', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/permissions');
    $response->assertStatus(200);
});

test('authorized user can create permissions', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/admin/permissions', [
        'name' => 'example.create'
    ]);
    $response->assertCreated();
});

test('authorized user can update permissions', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    $permission = Permission::create([
        'name' => 'example.create'
    ]);
    Sanctum::actingAs($user);
    $response = $this->putJson("/api/admin/permissions/{$permission->id}", [
        'name' => 'example.update',
    ]);
    $response
    ->assertStatus(200)
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'roles_count',
            'roles'
        ]
    ]);
});

test('authorized user can delete permissions', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    $permission = Permission::create([
        'name' => 'example.create'
    ]);
    Sanctum::actingAs($user);
    $response = $this->deleteJson("/api/admin/permissions/{$permission->id}");
    $response
    ->assertStatus(200)
    ->assertJson([
        'message' => 'Permission deleted successfully.'
    ]);
});

test('authorized user cannot delete permission with roles', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    $permission = Permission::create([
        'name' => 'example.create'
    ]);
    $permission->roles()->sync([1]);
    Sanctum::actingAs($user);
    $response = $this->deleteJson("/api/admin/permissions/{$permission->id}");
    $response
    ->assertStatus(409);
});