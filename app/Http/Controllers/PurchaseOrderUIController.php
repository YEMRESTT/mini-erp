<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderLog;
use Illuminate\Http\Request;

class PurchaseOrderUIController extends Controller
{
    /** 📌 Satın alma listesi */
    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'items'])
            ->latest()
            ->paginate(10);

        // 🔥 HER SİPARİŞ İÇİN TOPLAM HESAP
        $orders->getCollection()->transform(function ($order) {
            $order->calculated_total = $order->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            return $order;
        });

        return view('purchase.index', compact('orders'));
    }



    /** 📌 Yeni satın alma formu */
    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('purchase.create', compact('suppliers', 'products'));
    }

    /** 📌 Satın alma siparişi kaydetme */
    public function store(Request $request)
    {
        // Validasyon
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|json'
        ]);

        $items = json_decode($request->items, true) ?? [];

        if (empty($items)) {
            return back()->with('error', 'Sepete ürün eklemeden satın alma oluşturamazsın.');
        }

        // 🟢 1) Önce toplam hesapla
        $total = 0;
        foreach ($items as $item) {
            $qty   = max(1, intval($item['quantity'] ?? 1));
            $price = floatval($item['price'] ?? 0);
            $total += ($price * $qty);
        }

        // 🟢 2) Purchase order oluştur - BAŞTAN DOĞRU TOTAL İLE
        $order = PurchaseOrder::create([
            'supplier_id' => $request->supplier_id,
            'status'      => 'Pending',
            'total'       => $total, // ✅ Doğru toplam
        ]);

        // 🟢 3) Satırları ekle
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product) continue;

            $qty   = max(1, intval($item['quantity'] ?? 1));
            $price = floatval($item['price'] ?? 0);

            $lineTotal = $price * $qty;

            PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'product_id'        => $product->id,
                'quantity'          => $qty,
                'price'             => $price,
                'line_total'        => $lineTotal,
            ]);

            // 🟩 Stok Ekle
            if ($product->stock) {
                $product->stock->increment('quantity', $qty);
            }
        }

        // 🟢 4) İlk log
        PurchaseOrderLog::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id() ?? 1,
            'action'   => 'Satın alma siparişi oluşturuldu (Pending) - Toplam: ₺' . number_format($total, 2),
        ]);

        return redirect()
            ->route('purchase.index')
            ->with('success', 'Satın alma siparişi oluşturuldu! Toplam: ₺' . number_format($total, 2));
    }

    /** 📌 Satın alma detay sayfası */
    public function show(PurchaseOrder $order)
    {
        $order->load(['supplier', 'items.product', 'logs']);

        // 🧮 Ara toplam = satırların toplamı
        $subtotal = $order->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // KDV YOK
        $total = $subtotal;

        return view('purchase.show', compact(
            'order',
            'subtotal',
            'total'
        ));
    }


    /** 📌 Durum güncelleme */
    public function update(Request $request, PurchaseOrder $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Completed'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // ❌ Completed geri alınamaz
        if ($oldStatus === 'Completed' && $newStatus !== 'Completed') {
            return back()->with('error', 'Completed sipariş geri alınamaz.');
        }

        // 🔥 Pending → Approved = STOK ARTIR
        if ($oldStatus === 'Pending' && $newStatus === 'Approved') {

            foreach ($order->items as $item) {
                if ($item->product && $item->product->stock) {
                    $item->product->stock->increment('quantity', $item->quantity);
                }
            }

            PurchaseOrderLog::create([
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
                'action'   => 'Sipariş onaylandı, stoklar artırıldı',
            ]);
        }

        // 🟢 Approved → Completed
        if ($oldStatus === 'Approved' && $newStatus === 'Completed') {
            PurchaseOrderLog::create([
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
                'action'   => 'Sipariş tamamlandı',
            ]);
        }

        $order->update([
            'status' => $newStatus
        ]);

        return back()->with('success', 'Sipariş durumu güncellendi.');
    }



    /** 📌 Sipariş silme */
    public function destroy(PurchaseOrder $order)
    {
        // ❌ Completed silinemez
        if ($order->status === 'Completed') {
            return back()->with('error', 'Completed sipariş silinemez.');
        }

        // 🔁 Approved ise stok geri düş
        if ($order->status === 'Approved') {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->stock) {
                    $item->product->stock->decrement('quantity', $item->quantity);
                }
            }

            PurchaseOrderLog::create([
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
                'action'   => 'Sipariş silindi, stoklar geri çekildi',
            ]);
        }

        // 🟡 Pending ise sadece sil
        if ($order->status === 'Pending') {
            PurchaseOrderLog::create([
                'order_id' => $order->id,
                'user_id'  => auth()->id(),
                'action'   => 'Pending sipariş silindi',
            ]);
        }

        $order->delete();

        return redirect()
            ->route('purchase.index')
            ->with('success', 'Satın alma siparişi silindi.');
    }

}
