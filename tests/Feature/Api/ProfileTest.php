<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

test('guest cannot access profile', function () {
    $response = $this->getJson('/api/profile');

    $response->assertStatus(401);
});

test('authenticated user can access profile', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create([
        'role_id' => 2,
    ]);
    Sanctum::actingAs($user);
    $response = $this->getJson('/api/profile');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'avatar',
                'bio',
            ],
        ]);
});

test('user can update profile', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create([
        'role_id' => 2,
    ]);
    Storage::fake('public');
    $file = UploadedFile::fake()->image('avatar.png');
    Sanctum::actingAs($user);
    $response = $this->putJson('/api/profile', [
        'name' => 'John Doe',
        'email' => 'user@example.com',
        'avatar' => $file,
        'bio' => 'A normal user.',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'avatar',
                'bio',
            ],
        ]);
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Doe',
    ]);
});

test('user cannot upload file that is not an image', function () {
    $this->seed(RoleSeeder::class);
    Storage::fake('public');
    $user = User::factory()->create([
        'role_id' => 2,
    ]);
    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->create('document.pdf');

    $response = $this->putJson('/api/profile', [
        'name' => 'John Doe',
        'email' => 'user@example.com',
        'avatar' => $file,
        'bio' => 'A normal user',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('avatar');
});

test('user can update profile without bio and avatar', function () {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create([
        'role_id' => 2,
    ]);
    Sanctum::actingAs($user);
    $response = $this->putJson('/api/profile', [
        'name' => 'John Doe',
        'email' => 'user@example.com',
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'avatar',
                'bio',
            ],
        ]);
});
