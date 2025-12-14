<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;

class SalesOrderUIController extends Controller
{
    /** 📌 Satış siparişleri listesi */
    public function index()
    {
        $orders = SalesOrder::with('customer')
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('orders'));
    }

    /** 📌 Yeni satış siparişi formu */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();

        return view('sales.create', compact('customers', 'products'));
    }

    /** 📌 Satış siparişi kaydetme */
    public function store(Request $request)
    {
        // Validasyon
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|json'
        ]);

        $items = json_decode($request->items, true) ?? [];

        if (empty($items)) {
            return back()->with('error', 'Sepete ürün eklemeden sipariş oluşturamazsın.');
        }

        // 🟢 1) Önce ara toplam hesapla
        $subtotal = 0;
        foreach ($items as $item) {
            $qty = max(1, intval($item['quantity'] ?? 1));
            $price = floatval($item['price'] ?? 0);
            $subtotal += ($price * $qty);
        }

        // KDV hesapla
        $vat = $subtotal * 0.20; // %20 KDV
        $total = $subtotal + $vat;

        // 🟢 2) Sales order oluştur
        $order = SalesOrder::create([
            'customer_id' => $request->customer_id,
            'status' => 'Pending',
            'total' => $subtotal, // Ara toplam (KDV'siz)
        ]);

        // 🟢 3) Satırları ekle ve stok düş
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product) continue;

            $qty = max(1, intval($item['quantity'] ?? 1));
            $price = floatval($item['price'] ?? 0);

            // ✅ sales_order_id ekleniyor
            SalesOrderItem::create([
                'sales_order_id' => $order->id, // ✅ ÖNEMLİ: Bu eksikti
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
            ]);

            // 🟩 Stok azalt
            if ($product->stock) {
                $product->stock->decrement('quantity', $qty);
            }
        }

        return redirect()
            ->route('sales.index')
            ->with('success', 'Satış siparişi oluşturuldu! Toplam: ₺' . number_format($total, 2));
    }

    /** 📌 Satış siparişi detayı */
    public function show(SalesOrder $order)
    {
        $order->load(['customer', 'items.product', 'logs']);

        // 🧮 Hesaplar (HER ZAMAN)
        $subtotal   = $order->items->sum(fn($i) => $i->price * $i->quantity);
        $vatRate    = 0.20;
        $vatAmount  = round($subtotal * $vatRate, 2);
        $grandTotal = $subtotal + $vatAmount;

        return view('sales.show', compact(
            'order',
            'subtotal',
            'vatAmount',
            'grandTotal'
        ));
    }


    /** 📌 Durum güncelleme */
    public function update(Request $request, SalesOrder $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Completed,Cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Durum güncellendi!');
    }

    /** 📌 Sipariş silme */
    public function destroy(SalesOrder $order)
    {
        // Stok iade
        foreach ($order->items as $item) {
            if ($item->product && $item->product->stock) {
                $item->product->stock->increment('quantity', $item->quantity);
            }
        }

        $order->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sipariş silindi ve stok iade edildi!');
    }
}
