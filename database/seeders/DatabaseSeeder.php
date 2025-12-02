<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\ProductCategoryPivot;
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
        // 0) Admin kullanıcıyı oluştur
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        // ------------------------------
        // 1) KATEGORİLER
        // ------------------------------
        ProductCategory::factory(10)->create();

        // ------------------------------
        // 2) ÜRÜNLER + STOK + RESİMLER
        // ------------------------------
        Product::factory(50)->create()->each(function ($product) {
            ProductStock::factory()->create([
                'product_id' => $product->id,
            ]);

            ProductImage::factory(2)->create([
                'product_id' => $product->id,
            ]);

            $categoryIds = ProductCategory::inRandomOrder()
                ->take(rand(1, 3))
                ->pluck('id');
            $product->categories()->attach($categoryIds);
        });

        // ------------------------------
        // 3) MÜŞTERİLER + NOTLAR
        // ------------------------------
        Customer::factory(20)->create()->each(function ($customer) {
            CustomerNote::factory(2)->create([
                'customer_id' => $customer->id,
            ]);
        });

        // ------------------------------
        // 4) TEDARİKÇİLER + BELGELER
        // ------------------------------
        Supplier::factory(10)->create()->each(function ($supplier) {
            SupplierDocument::factory(2)->create([
                'supplier_id' => $supplier->id,
            ]);
        });

        // ------------------------------
        // 5) SATIN ALMA SİPARİŞLERİ + KALEMLER + LOG
        // ------------------------------
        PurchaseOrder::factory(15)->create()->each(function ($order) {
            PurchaseOrderItem::factory(3)->create([
                'order_id' => $order->id,
                'product_id' => Product::inRandomOrder()->first()->id,
            ]);

            PurchaseOrderLog::factory(2)->create([
                'order_id' => $order->id,
            ]);
        });

        // ------------------------------
        // 6) SATIŞ SİPARİŞLERİ + KALEMLER + LOG + FATURA
        // ------------------------------
        SalesOrder::factory(30)->create()->each(function ($order) {

            $items = SalesOrderItem::factory(3)->create([
                'order_id' => $order->id,
                'product_id' => Product::inRandomOrder()->first()->id,
            ]);

            // toplam fiyat
            $order->total = $items->sum(fn ($i) => $i->quantity * $i->price);
            $order->save();

            // loglar
            SalesOrderLog::factory(2)->create([
                'order_id' => $order->id
            ]);

            // fatura (şansa bağlı)
            if (rand(0, 1)) {
                $invoice = Invoice::factory()->create([
                    'sales_order_id' => $order->id,
                ]);

                InvoiceItem::factory(3)->create([
                    'invoice_id' => $invoice->id,
                    'product_id' => Product::inRandomOrder()->first()->id,
                ]);
            }
        });

        // ------------------------------
        // 7) HAFTALIK RAPORLAR
        // ------------------------------
        WeeklyReport::factory(8)->create();

        // ------------------------------
        // 8) BİLDİRİMLER
        // ------------------------------
        Notification::factory(30)->create();

        echo "\n🔥 FULL SEED BAŞARIYLA TAMAMLANDI! 🔥\n";
    }
}
