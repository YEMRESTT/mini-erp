<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Ürünler
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4">

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('products.create') }}"
               class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
                Yeni Ürün Ekle
            </a>
        </div>

        <div class="space-y-4">

            @foreach ($products as $product)
                <div class="bg-gray-400 rounded-xl shadow hover:shadow-md transition p-3 flex gap-4 items-center">

                    {{-- Küçük Görsel --}}
                    @php $primaryImage = $product->images->first(); @endphp

                    <div class="w-20 h-20 rounded bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if($primaryImage)
                            <img src="{{ asset('storage/' . $primaryImage->image_url) }}"
                                 class="w-full h-full object-cover">
                        @endif
                    </div>


                    {{-- Bilgi Bölümü --}}
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                            {{ $product->name }}
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">SKU: {{ $product->sku }}</p>

                        {{-- Stok Badge --}}
                        @if($product->stock)
                            @php
                                $qty = $product->stock->quantity;
                                $level = $product->stock->min_level;
                                $color = $qty <= $level ? 'bg-red-500' : 'bg-green-600';
                            @endphp
                            <span class="mt-2 inline-block px-2 py-1 text-[11px] rounded text-black {{ $color }}">
                                Stok: {{ $qty }}
                            </span>
                        @else
                            <span class="mt-2 inline-block px-2 py-1 text-[11px] rounded bg-gray-500 text-black">
                                Stok Yok
                            </span>
                        @endif
                    </div>

                    {{-- Güncel Fiyat --}}
                    @php
                        $lastPrice = $product->priceLogs->sortByDesc('created_at')->first()->new_price ?? null;
                    @endphp

                    @if($lastPrice)
                        <span class="mt-2 inline-block px-2 py-1 text-[12px] rounded bg-blue-600 text-black">
                            Fiyat: ₺{{ number_format($lastPrice, 2, ',', '.') }}
                        </span>
                    @else
                        <span class="mt-2 inline-block px-2 py-1 text-[12px] rounded bg-gray-400 text-black">
                            Fiyat Yok
                        </span>
                    @endif


                    {{-- İşlem Düğmeleri --}}
                    <div class="flex flex-col items-end gap-1 text-sm">
                        <a href="{{ route('products.show', $product->id) }}"
                           class="text-blue-600 hover:underline">Detay</a>

                        <a href="{{ route('products.edit', $product->id) }}"
                           class="text-yellow-600 hover:underline">Düzenle</a>

                        <form action="{{ route('products.destroy', $product->id) }}"
                              method="POST"
                              onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Sil</button>
                        </form>
                    </div>

                </div>
            @endforeach

        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>

</x-app-layout>
