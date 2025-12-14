<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('stock:check-critical')
    ->dailyAt('09:00');


Schedule::command('notifications:cleanup')
    ->dailyAt('02:00');


Schedule::command('purchase:check-delayed')
    ->dailyAt('09:30');
