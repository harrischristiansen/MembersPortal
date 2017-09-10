<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\BuildCache::class,
        Commands\FacebookEvents::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('inspire')
            ->hourly()
            ->appendOutputTo(getcwd().'/storage/logs/laravel.log');
        $schedule->command('cache:build')->hourly();
        //$schedule->command('events:pull')->hourly();
    }
}
