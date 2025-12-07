<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔹 Toplam Satış
        $totalSales = SalesOrderItem::sum(DB::raw('quantity * price'));

        // 🔹 Bu Ay Gelir
        $monthlyRevenue = SalesOrderItem::whereMonth('created_at', now()->month)
            ->sum(DB::raw('quantity * price'));

        // 🔹 En Çok Satan Ürün
        $topProduct = SalesOrderItem::select('product_id')
            ->with('product')
            ->groupBy('product_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->first();

        // 🔹 Kritik Stok
        $criticalStockCount = ProductStock::whereColumn('quantity', '<=', 'min_level')->count();

        // 🔹 Toplam Sipariş
        $orderCount = SalesOrder::count();

        // 🔹 Haftalık Satış Grafiği (7 Gün)
        $weeklySales = SalesOrderItem::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(quantity * price) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(179))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $days = [];
        $totals = [];

        foreach ($weeklySales as $sale) {
            $days[] = Carbon::parse($sale->date)->format('d.m');
            $totals[] = number_format($sale->total, 2, '.', '');
        }

        return view('dashboard', compact(
            'totalSales',
            'monthlyRevenue',
            'topProduct',
            'criticalStockCount',
            'orderCount',
            'days',
            'totals',
        ));
    }
}

