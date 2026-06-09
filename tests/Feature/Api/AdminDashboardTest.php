<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('guest cannot access the dashboard', function () {
    $response = $this->getJson('/api/admin');

    $response->assertStatus(401);
});

test('unauthorized users cannot access the dashboard', function () {
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 2
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin');

    $response->assertStatus(403);
});

test('authorized users can access the dashboard', function(){
    $this->seed();
    $user = User::factory()->create([
        'role_id' => 1
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/admin');

    $response
    ->assertStatus(200)
    ->assertJsonStructure([
        'data' => [
            'stats' => [
                'users_count',
                'roles_count',
                'admins_count'
            ],
            'latest_logs'
        ]
    ]);
});