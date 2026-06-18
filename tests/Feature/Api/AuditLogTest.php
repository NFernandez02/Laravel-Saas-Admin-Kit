<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('guests cannot access auditlog', function () {
    $response = $this->getJson('/api/v1/admin/logs');

    $response->assertStatus(401);
});

test('unauthorized users cannot access auditlog', function () {
    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/v1/admin/logs');

    $response->assertStatus(403);
});

test('authorized users can access auditlog', function () {
    $adminRole = Role::where('name', 'admin')->firstOrFail();
    $user = User::factory()->create([
        'role_id' => $adminRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/v1/admin/logs');

    $response->assertStatus(200);
});
