<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = ProductStock::with('product')
            ->orderBy('quantity', 'asc')
            ->paginate(15);

        return view('stock.index', compact('stocks'));
    }

    public function updateStock(Request $request, $id)
    {
        $stock = ProductStock::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer',
            'description' => 'required|string|max:255',
            'type' => 'required|in:in,out',
        ]);

        if ($request->type === 'in') {
            $stock->quantity += $request->quantity;
        } else {
            $stock->quantity -= $request->quantity;
        }

        $stock->save();

        // Kritik stok kontrolü
        if ($stock->quantity <= $stock->min_level) {
            \App\Models\Notification::create([
                'product_id' => $stock->product_id,
                'type' => 'critical_stock',
                'title' => 'Kritik Stok Uyarısı',
                'message' => $stock->product->name . ' kritik stok seviyesine düştü!',
                'is_read' => false,
            ]);
        }

        // Log Kaydı
        $stock->movements()->create([
            'quantity' => $request->quantity,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Stok başarıyla güncellendi!');
    }

    public function updateMinLevel(Request $request, $id)
    {
        $request->validate([
            'min_level' => 'required|integer|min:0',
        ]);

        $stock = ProductStock::findOrFail($id);
        $stock->min_level = $request->min_level;
        $stock->save();

        return back()->with('success', 'Minimum stok seviyesi güncellendi!');
    }
}
