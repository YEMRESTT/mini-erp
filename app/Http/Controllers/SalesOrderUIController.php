<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;

class SalesOrderUIController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with('customer')->latest()->paginate(10);
        return view('sales.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $items = json_decode($request->items, true); // 🔥 ZORUNLU! (string → array)

        if (!$items || !is_array($items)) {
            return back()->with('error', 'Lütfen ürün ekleyin!');
        }

        $order = SalesOrder::create([
            'customer_id' => $request->customer_id,
            'status' => 'Pending',
            'total' => 0
        ]);

        foreach ($items as $item) {
            $product = Product::find($item['id']);

            SalesOrderItem::create([
                'sales_order_id' => $order->id, // 🔥 artık NULL değil!
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            // 🔥 Stok düş
            $product->stock->decrement('quantity', $item['quantity']);
        }

        $order->update([
            'total' => $order->items()->sum(\DB::raw('price * quantity'))
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Satış siparişi oluşturuldu!');
    }

    public function show($id)
    {
        $order = SalesOrder::with(['customer', 'items.product', 'logs'])
            ->findOrFail($id);

        return view('sales.show', compact('order'));
    }


    public function update(Request $request, SalesOrder $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Completed'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Sipariş durumu güncellendi! 🎯');
    }




}
