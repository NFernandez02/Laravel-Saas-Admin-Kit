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

test('guest cannot access roles', function () {
    $response = $this->getJson('/api/admin/roles');

    $response->assertStatus(401);
});

test('unauthorized users cannot access roles', function () {

    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/roles');

    $response->assertStatus(403);
});

test('authorized users can access roles', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/roles');

    $response->assertStatus(200);
});

test('authorized users can create roles', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole,
    ]);
    $permissions = Permission::whereIn('name', [
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
    ])->pluck('id')->all();
    Sanctum::actingAs($user);
    $response = $this->postJson('/api/admin/roles', [
        'name' => 'moderator',
        'permissions' => $permissions,
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('roles', [
        'name' => 'moderator',
    ]);
});

test('authorized users can update roles', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole,
    ]);
    Sanctum::actingAs($user);
    $role = Role::create([
        'name' => 'manager',
    ]);
    $permissions = Permission::whereIn('name', [
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
    ])->pluck('id')->all();
    $permission = Permission::where('name', 'users.view')->firstOrFail();
    $role->permissions()->sync([$permission->id]);
    $response = $this->putJson("/api/admin/roles/{$role->id}", [
        'name' => 'moderator',
        'permissions' => $permissions,
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'users_count',
                'permissions',
            ],
        ]);
    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'moderator',
    ]);
});

test('authorized users can delete roles', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $role = Role::create([
        'name' => 'moderator',
    ]);
    $permission = Permission::where('name', 'users.view')->firstOrFail();
    $role->permissions()->sync([$permission->id]);
    $response = $this->deleteJson("/api/admin/roles/{$role->id}");

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Role deleted successfully.',
        ]);
    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

test('authorized users cannot delete roles with a user', function () {

    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $role = Role::create([
        'name' => 'moderator',
    ]);
    User::factory()->create([
        'role_id' => $role->id,
    ]);
    $permission = Permission::where('name', 'users.view')->firstOrFail();
    $role->permissions()->sync([$permission->id]);
    $response = $this->deleteJson("/api/admin/roles/{$role->id}");
    $response
        ->assertStatus(409);
});
