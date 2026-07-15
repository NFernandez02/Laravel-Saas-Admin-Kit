<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('guest cannot access permissions', function () {
    $response = $this->getJson('/api/v1/admin/permissions');

    $response->assertStatus(401);
});

test('unauthorized user cannot access permissions', function () {

    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/v1/admin/permissions');
    $response->assertStatus(403);
});

test('authorized user can access permissions', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/v1/admin/permissions');
    $response->assertStatus(200);
});

test('authorized user can access all permissions', function () {
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/v1/admin/permissions/all');
    $response->assertStatus(200);
});

test('authorized user can create permissions', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/v1/admin/permissions', [
        'name' => 'example.create',
    ]);
    $response->assertCreated();
    $this->assertDatabaseHas('permissions', [
        'name' => 'example.create',
    ]);
});

test('authorized user can update permissions', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    $permission = Permission::create([
        'name' => 'example.create',
    ]);
    Sanctum::actingAs($user);
    $response = $this->putJson("/api/v1/admin/permissions/{$permission->id}", [
        'name' => 'example.update',
    ]);
    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'roles_count',
                'roles',
            ],
        ]);
    $this->assertDatabaseHas('permissions', [
        'id' => $permission->id,
        'name' => 'example.update',
    ]);
});

test('authorized user can delete permissions', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole,
    ]);
    $permission = Permission::create([
        'name' => 'example.create',
    ]);
    Sanctum::actingAs($user);
    $response = $this->deleteJson("/api/v1/admin/permissions/{$permission->id}");
    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Permission deleted successfully.',
        ]);
    $this->assertDatabaseMissing('permissions', [
        'id' => $permission->id,
    ]);
});

test('authorized user cannot delete permission with roles', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    $permission = Permission::create([
        'name' => 'example.create',
    ]);
    $role = Role::where('name', 'admin')->firstOrFail();
    $permission->roles()->sync([$role->id]);
    Sanctum::actingAs($user);
    $response = $this->deleteJson("/api/v1/admin/permissions/{$permission->id}");
    $response
        ->assertStatus(409);
});
