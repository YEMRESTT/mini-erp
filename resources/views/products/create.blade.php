<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Yeni Ürün Ekle
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

            <form action="{{ route('products.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="bg-white p-6 shadow rounded-lg">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">Ürün Adı</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}"
                       class="w-full border rounded p-2"
                       required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Barkod</label>
                <input type="text" name="barcode" value="{{ old('barcode') }}"
                       class="w-full border rounded p-2">
            </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Fiyat (₺)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                           class="w-full border rounded p-2" required>
                </div>



                <div class="mb-4">
                <label class="block font-medium mb-1">Durum</label>
                <select name="status" required
                        class="w-full border rounded p-2">
                    <option value="active">Aktif</option>
                    <option value="inactive">Pasif</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Kategoriler</label>
                <select name="categories[]" multiple required
                        class="w-full border rounded p-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    (CTRL / CMD ile birden fazla seçebilirsin)
                </p>
            </div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">Ürün Görselleri</label>
                    <input type="file" name="images[]"
                           multiple
                           class="w-full border rounded p-2">
                    <p class="text-sm text-gray-500 mt-1">Birden fazla görsel seçebilirsin.</p>
                </div>


                <div class="mb-4">
                <label class="block font-medium mb-1">Açıklama</label>
                <textarea name="description"
                          class="w-full border rounded p-2"
                          rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-200">
                    Geri
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-black rounded hover:bg-blue-700">
                    Kaydet
                </button>
            </div>

        </form>

    </div>

</x-app-layout>
