<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\Supplier;
use App\Models\SupplierDocument;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderLog;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderLog;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\WeeklyReport;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\ProductPriceLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password')
            ]
        );

        ProductCategory::factory(10)->create();


        Product::factory(50)->create()->each(function ($product) {
            ProductStock::factory()->create([
                'product_id' => $product->id,
                'min_level' => fake()->numberBetween(5, 20),
            ]);

            ProductImage::factory(2)->create([
                'product_id' => $product->id,
            ]);

            $product->categories()->attach(
                ProductCategory::inRandomOrder()->take(rand(1,3))->pluck('id')
            );

            // 💰 Ürün fiyatı oluştur
            $product->update([
                'price' => fake()->randomFloat(2, 50, 2000),
            ]);

            // 📈 Fiyat geçmişi oluştur
            $price = $product->price;
            for ($i = 0; $i < rand(1, 5); $i++) {
                $old = $price;
                $price = fake()->randomFloat(2, 50, 2000);

                $product->priceLogs()->create([
                    'old_price' => $old,
                    'new_price' => $price,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);

                $product->update(['price' => $price]);
            }
        });


        Supplier::factory(10)->create()->each(function ($supplier) {
            SupplierDocument::factory(1)->create([
                'supplier_id' => $supplier->id,
            ]);
        });

        // PURCHASE ORDERS
        PurchaseOrder::factory(20)->create([
            'status' => 'pending'
        ])->each(function ($order) {

            $items = PurchaseOrderItem::factory(rand(1,4))->create([
                'order_id' => $order->id
            ]);

            $order->total_amount = $items->sum(fn($i) => $i->quantity * $i->price);
            $order->status = fake()->randomElement(['Pending', 'Approved', 'Completed']);
            $order->save();

            PurchaseOrderLog::factory(2)->create([
                'order_id' => $order->id,

            ]);

            foreach ($items as $item) {
                $stock = ProductStock::firstOrCreate(['product_id' => $item->product_id]);
                $stock->increment('quantity', $item->quantity);
            }
        });

        // SALES ORDERS
        $customerIds = \App\Models\Customer::pluck('id')->toArray();

        SalesOrder::factory(30)->create()->each(function ($order) use ($customerIds) {



            $items = \App\Models\SalesOrderItem::factory(rand(1,4))->create([
                'order_id' => $order->id,
            ]);

            $order->total = $items->sum(fn($i) => $i->quantity * $i->price);
            $order->status = fake()->randomElement(['Pending', 'Approved', 'Completed']);
            $order->save();

            \App\Models\SalesOrderLog::factory(2)->create([
                'order_id' => $order->id,
                'user_id' => 1,
            ]);

            foreach ($items as $item) {
                $stock = \App\Models\ProductStock::firstOrCreate(['product_id' => $item->product_id]);
                $stock->decrement('quantity', $item->quantity);
            }

            if (rand(0,1)) {
                $invoice = \App\Models\Invoice::factory()->create([
                    'sales_order_id' => $order->id,
                ]);

                \App\Models\InvoiceItem::factory($items->count())->create([
                    'invoice_id' => $invoice->id,
                ]);
            }
        });



        WeeklyReport::factory(6)->create();
        Notification::factory(30)->create();

        echo "\n🔥 FULL ERP SEEDING TAMAMLANDI! 🔥\n";
    }
}
