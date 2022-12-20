<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('backup:clean')->dailyAt('03:15')->thenPing(config('services.healthcheck.backup_cleanup'));
        $schedule->command('backup:run')->dailyAt('03:30')->thenPing(config('services.healthcheck.backup_run'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
