<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['role_id' => 1]),
            'action' => $this->faker->randomElement([
                'create',
                'update',
                'delete',
            ]),
            'target_type' => $this->faker->randomElement([
                'User',
                'Role',
                'Permission',
            ]),
            'target_id' => $this->faker->numberBetween(1, 1000),
            'description' => $this->faker->sentence(),
        ];
    }
}
