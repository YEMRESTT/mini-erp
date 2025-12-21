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


Schedule::command('products:monthly-price-log')
    ->monthlyOn(1, '02:00');


Schedule::command('reports:weekly-sales')
    ->weeklyOn(7, '23:55'); // pazar gecesi


Schedule::command('customers:mark-passive')
    ->dailyAt('02:00');

Schedule::command('notifications:cleanup')
    ->dailyAt('03:00');


Schedule::command('invoices:check-overdue')
    ->dailyAt('08:00');
