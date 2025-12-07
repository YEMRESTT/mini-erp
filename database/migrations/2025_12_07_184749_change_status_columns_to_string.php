<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // CHECK constraintleri sil
        DB::statement('ALTER TABLE purchase_orders DROP CONSTRAINT IF EXISTS purchase_orders_status_check;');
        DB::statement('ALTER TABLE sales_orders DROP CONSTRAINT IF EXISTS sales_orders_status_check;');

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
        });
    }

    public function down(): void
    {
        // Gerekirse geri dönüş için ENUM eklenebilir
    }
};
