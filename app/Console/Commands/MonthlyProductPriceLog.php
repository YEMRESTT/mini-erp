<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class MonthlyProductPriceLog extends Command
{
    protected $signature = 'products:monthly-price-log';

    protected $description = 'Her ay ürün fiyatlarını price log tablosuna kaydeder';

    public function handle()
    {
        $products = Product::all();

        foreach ($products as $product) {

            // aynı ay içinde tekrar log atılmasını engelle
            $alreadyLogged = $product->priceLogs()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->exists();

            if ($alreadyLogged) {
                continue;
            }

            $product->priceLogs()->create([
                'old_price' => $product->price,
                'new_price' => $product->price,
                'created_at' => now(),
            ]);
        }

        $this->info('Aylık ürün fiyat logları oluşturuldu.');
    }
}
