<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('guests cannot access auditlog', function () {
    $response = $this->getJson('/api/admin/logs');

    $response->assertStatus(401);
});

test('unauthorized users cannot access auditlog', function () {
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 2,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/logs');

    $response->assertStatus(403);
});

test('authorized users can access auditlog', function () {
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin/logs');

    $response->assertStatus(200);
});
