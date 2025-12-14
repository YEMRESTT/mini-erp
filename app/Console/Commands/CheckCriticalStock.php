<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductStock;
use App\Models\Notification;

class CheckCriticalStock extends Command
{
    protected $signature = 'stock:check-critical';

    protected $description = 'Kritik stok seviyesindeki ürünleri kontrol eder';

    public function handle()
    {
        $criticalStocks = ProductStock::with('product')
            ->whereColumn('quantity', '<=', 'min_level')
            ->get();

        foreach ($criticalStocks as $stock) {

            // Aynı gün aynı ürün için tekrar bildirim oluşturma
            $alreadyNotified = Notification::where('type', 'critical_stock')
                ->where('message', 'like', '%'.$stock->product->name.'%')

                ->whereDate('created_at', now())
                ->exists();

            if ($alreadyNotified) {
                continue;
            }


            Notification::create([
                'type'    => 'critical_stock',
                'title'   => 'Kritik Stok Uyarısı',
                'message' => "{$stock->product->name} ürünü kritik stok seviyesinde ({$stock->quantity})",
                'user_id' => 1, // admin
            ]);

        }

        $this->info('Kritik stok kontrolü tamamlandı.');
    }
}
