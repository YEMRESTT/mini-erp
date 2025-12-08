<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Ürünü Düzenle — {{ $product->name }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4">

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <strong>Hata!</strong>
                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white p-6 shadow rounded-lg">

            @csrf
            @method('PUT')

            {{-- Ürün Adı --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Ürün Adı</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                       class="w-full border rounded p-2" required>
            </div>

            {{-- SKU --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">SKU</label>
                <input type="text" value="{{ $product->sku }}"
                       class="w-full border rounded p-2 bg-gray-100" disabled>
            </div>

            {{-- Barkod --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Barkod</label>
                <input type="text" name="barcode"
                       value="{{ old('barcode', $product->barcode) }}"
                       class="w-full border rounded p-2">
            </div>

            {{-- Durum --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Durum</label>
                <select name="status" class="w-full border rounded p-2" required>
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>

            {{-- Kategoriler --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Kategoriler</label>
                <select name="categories[]" multiple class="w-full border rounded p-2" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->categories->pluck('id')->contains($category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    (CTRL / CMD ile birden fazla seç)
                </p>
            </div>

            {{-- Fiyat --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Fiyat (₺)</label>
                <input type="number" name="price"
                       value="{{ old('price', $product->price) }}"
                       step="0.01" min="0"
                       class="w-full border rounded p-2" required>
            </div>

            {{-- Açıklama --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Açıklama</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded p-2">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Kaydet --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-200">
                    Geri
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Güncelle
                </button>
            </div>

        </form>
    </div>

</x-app-layout>
