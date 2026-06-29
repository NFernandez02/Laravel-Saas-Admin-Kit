<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:cleanup-audit-logs')]
#[Description('Command description')]
class CleanupAuditLogs extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $deleted = AuditLog::query()
            ->where('created_at', '<', now()->subMonths(6))
            ->delete();

        $this->info('Deleted {$deleted} audit logs.');

        return self::SUCCESS;
    }
}
