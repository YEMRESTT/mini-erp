<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;

class CleanupNotifications extends Command
{
    protected $signature = 'notifications:cleanup';

    protected $description = '60 günden eski bildirimleri siler';

    public function handle()
    {
        $deleted = Notification::where('created_at', '<', now()->subDays(60))
            ->delete();

        $this->info("$deleted adet eski bildirim silindi.");
    }
}
