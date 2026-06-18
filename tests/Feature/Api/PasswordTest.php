<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('authorized user can change password', function () {
    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->putJson('/api/v1/password', [
        'current_password' => 'password',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Password changed successfully.',
        ]);
    $user->refresh();
    expect(
        Hash::check('newpassword', $user->password)
    )->toBeTrue();
});

test('cannot change password without typing correct current password', function () {

    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->putJson('/api/v1/password', [
        'current_password' => 'pass',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);
    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('current_password');
});

test('cannot change password if password confirmation is not correct', function () {
    $userRole = Role::where('name', 'user')->firstOrFail();
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'role_id' => $userRole->id,
    ]);
    Sanctum::actingAs($user);
    $response = $this->putJson('/api/v1/password', [
        'current_password' => 'password',
        'password' => 'newpassword',
        'password_confirmation' => 'password',
    ]);
    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors('password');
});
