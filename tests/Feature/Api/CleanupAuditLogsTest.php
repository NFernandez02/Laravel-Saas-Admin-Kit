<?php

use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('cleanup audit logs command deletes old logs', function () {
    AuditLog::factory()->create([
        'created_at' => now()->subMonths(7),
    ]);

    AuditLog::factory()->create([
        'created_at' => now()->subMonths(1),
    ]);

    $this->artisan('app:cleanup-audit-logs')
        ->assertSuccessful();

    expect(AuditLog::count())->toBe(1);
});
