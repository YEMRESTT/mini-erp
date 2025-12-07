<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('total_amount', 12, 2)->default(0)->after('status');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->decimal('total_amount', 12, 2)->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};

