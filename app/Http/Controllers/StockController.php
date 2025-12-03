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

        // Log Kaydı
        $stock->movements()->create([
            'quantity' => $request->quantity,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Stok başarıyla güncellendi!');
    }
}
