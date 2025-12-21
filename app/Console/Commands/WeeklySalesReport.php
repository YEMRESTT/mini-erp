<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\WeeklyReport;
use Illuminate\Support\Facades\DB;

class WeeklySalesReport extends Command
{
    protected $signature = 'reports:weekly-sales';

    protected $description = 'Haftalık satış raporu oluşturur';

    public function handle()
    {
        $start = now()->startOfWeek();
        $end   = now()->endOfWeek();

        // Aynı hafta tekrar yazma
        $exists = WeeklyReport::whereBetween('created_at', [$start, $end])->exists();
        if ($exists) {
            $this->info('Bu hafta için rapor zaten oluşturulmuş.');
            return;
        }


        $totalSales = SalesOrder::whereBetween('created_at', [$start, $end])
            ->sum('total');

        $topProduct = SalesOrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();



        $topCustomer = SalesOrder::select(
            'customer_id',
            DB::raw('COUNT(*) as total_orders')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('customer_id')
            ->orderByDesc('total_orders')
            ->with('customer')
            ->first();



        WeeklyReport::create([
            'total_sales' => $totalSales,
            'top_product' => $topProduct?->product?->name ?? '—',
            'top_customer'=> $topCustomer?->customer?->name ?? '—',
        ]);



        $this->info('Haftalık satış raporu oluşturuldu.');
    }

}
