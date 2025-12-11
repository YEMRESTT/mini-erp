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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
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

            // 💰 Fiyat
            $price = fake()->randomFloat(2, 50, 1500);
            $product->update(['price' => $price]);

            // 📈 Fiyat log
            for ($i = 0; $i < rand(1,5); $i++) {
                $old = $price;
                $price = fake()->randomFloat(2, 50, 1500);

                $product->priceLogs()->create([
                    'old_price' => $old,
                    'new_price' => $price,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now')
                ]);

                $product->update(['price' => $price]);
            }
        });

        // CUSTOMERS
        Customer::factory(50)->create()->each(function ($c) {
            CustomerNote::factory(2)->create([
                'customer_id' => $c->id
            ]);
        });

        Supplier::factory(10)->create()->each(function ($s) {
            SupplierDocument::factory()->create([
                'supplier_id' => $s->id
            ]);
        });


        // PURCHASE ORDERS
        PurchaseOrder::factory(20)->create()->each(function ($order) {

            $items = PurchaseOrderItem::factory(rand(1,4))->create([
                'purchase_order_id' => $order->id
            ]);

            $total = $items->sum(fn($i) => $i->quantity * $i->price);

            $order->update([
                'status' => fake()->randomElement(['Pending', 'Approved', 'Completed']),
                'total_amount' => $total
            ]);

            PurchaseOrderLog::factory(2)->create([
                'order_id' => $order->id
            ]);
        });




        // SALES ORDERS
        $customerIds = Customer::pluck('id')->toArray();

        SalesOrder::factory(30)->create()->each(function ($order) use ($customerIds) {

            // 🆕 MÜŞTERİ BAĞLANDI!
            $order->update([
                'customer_id' => fake()->randomElement($customerIds)
            ]);

            $items = SalesOrderItem::factory(rand(1,4))->create([
                'sales_order_id' => $order->id
            ]);

            $order->update([
                'total' => $items->sum(fn($i) => $i->quantity * $i->price),
                'status' => fake()->randomElement(['Pending', 'Approved', 'Completed'])
            ]);

            SalesOrderLog::factory(2)->create([
                'order_id' => $order->id,
                'user_id' => 1
            ]);
        });

        WeeklyReport::factory(6)->create();
        Notification::factory(30)->create();

        echo "\n🔥 FULL ERP SEEDING TAMAMLANDI! 🔥\n";
    }
}
