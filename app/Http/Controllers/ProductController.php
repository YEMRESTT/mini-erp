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
        $products = Product::with('stock')->latest()->paginate(10);
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

        ]);

        $product = Product::create($request->only('name', 'sku', 'barcode', 'description', 'status'));

        $product->categories()->sync($request->categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $index === 0 ? true : false,
                ]);
            }
        }

        ProductStock::create([
            'product_id' => $product->id,
            'quantity'   => 0,
            'min_level'  => 5,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
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
            'categories' => 'required|array'
        ]);

        $product->update($request->only('name', 'barcode', 'description', 'status'));
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
