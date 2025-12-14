<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PurchaseOrder;
use App\Models\Notification;

class CheckDelayedPurchaseOrders extends Command
{
    protected $signature = 'purchase:check-delayed';

    protected $description = 'Teslim tarihi geçmiş satın alma siparişlerini kontrol eder';

    public function handle()
    {
        $orders = PurchaseOrder::whereDate('expected_date', '<', now())
            ->where('status', '!=', 'Completed')
            ->get();

        foreach ($orders as $order) {

            // aynı gün aynı sipariş için tekrar bildirim atma
            $alreadyNotified = Notification::where('type', 'delayed_purchase_order')
                ->where('message', 'like', '%#'.$order->id.'%')
                ->whereDate('created_at', now())
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            Notification::create([
                'type'    => 'delayed_purchase_order',
                'message' => "Satın alma siparişi gecikti: #{$order->id}",
            ]);
        }

        $this->info('Geciken satın alma siparişleri kontrol edildi.');
    }
}
