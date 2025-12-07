<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['stock', 'priceLogs'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'sku'        => 'required|string|unique:products',
            'barcode'    => 'nullable|string|unique:products',
            'status'     => 'required|string',
            'categories' => 'required|array',
            'price'      => 'required|numeric|min:0',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'description' => $request->description,
            'status' => $request->status,
            'price' => $request->price,
        ]);

        $product->categories()->sync($request->categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        ProductStock::create([
            'product_id' => $product->id,
            'quantity'   => 0,
            'min_level'  => 5,
        ]);

        // İlk fiyat log kaydı
        $product->priceLogs()->create([
            'old_price' => null,
            'new_price' => $request->price,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu!');
    }

    public function show($id)
    {
        $product = Product::with([
            'categories',
            'images',
            'stock',
            'priceLogs',
            'salesItems.order',
            'purchaseItems.order',
            'stockMovements'
        ])->findOrFail($id);

        // Grafik için veri
        $priceHistory = $product->priceLogs->sortBy('created_at');
        $priceDates = $priceHistory->pluck('created_at')->map(fn($d) => $d->format('d.m'))->toArray();
        $priceValues = $priceHistory->pluck('new_price')->toArray();

        $recentSales = $product->salesItems->sortByDesc('created_at')->take(5);
        $recentPurchases = $product->purchaseItems->sortByDesc('created_at')->take(5);

        return view('products.show', compact(
            'product',
            'priceDates',
            'priceValues',
            'recentSales',
            'recentPurchases'
        ));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'status'     => 'required|string',
            'categories' => 'required|array',
            'price'      => 'required|numeric|min:0',
        ]);

        // Eski fiyatı al
        $oldPrice = $product->price;

        // Ürünü güncelle
        $product->update([
            'name' => $request->name,
            'barcode' => $request->barcode,
            'description' => $request->description,
            'status' => $request->status,
            'price' => $request->price,
        ]);

        // Fiyat değişmişse PriceLog kaydı oluştur
        if ($oldPrice != $request->price) {
            $product->priceLogs()->create([
                'old_price' => $oldPrice,
                'new_price' => $request->price,
            ]);
        }

        $product->categories()->sync($request->categories);

        return redirect()->route('products.index')
            ->with('success', 'Ürün güncellendi!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Ürün silindi!');
    }
}
