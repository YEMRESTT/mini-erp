<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            {{ $product->name }}
            <span class="text-gray-500 text-sm">({{ $product->sku }})</span>
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Sol - Görseller --}}
        <div class="col-span-1 bg-white p-4 rounded-lg shadow-md">
            @if($product->images->count())
                <img src="{{ asset('storage/'.$product->images->first()->image_url) }}"
                     class="w-full h-64 object-cover rounded-lg mb-4 shadow">

                <div class="flex gap-2 overflow-x-auto pb-1">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/'.$img->image_url) }}"
                             class="w-16 h-16 rounded-lg object-cover border hover:scale-105 transition cursor-pointer shadow-sm">
                    @endforeach
                </div>
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 font-medium">
                    Ürün Görseli Yok
                </div>
            @endif
        </div>

        {{-- Sağ - Bilgiler --}}
        <div class="col-span-2 bg-white p-6 rounded-lg shadow-md space-y-4">

            <p><span class="font-semibold text-gray-800">Barkod:</span> {{ $product->barcode ?? '—' }}</p>

            <div>
                <span class="font-semibold text-gray-800">Açıklama:</span><br>
                <span class="text-gray-700">{{ $product->description ?? '—' }}</span>
            </div>

            {{-- Kategoriler --}}
            <div>
                <span class="font-semibold text-gray-800">Kategoriler:</span><br>
                @forelse($product->categories as $category)
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs mr-1">
                        {{ $category->name }}
                    </span>
                @empty
                    <span class="text-gray-500 text-sm">Kategori Yok</span>
                @endforelse
            </div>

            {{-- Stok --}}
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-800">Stok Durumu:</span>
                @php
                    $qty = $product->stock->quantity ?? 0;
                    $level = $product->stock->min_level ?? 0;
                @endphp

                <span class="px-3 py-1 rounded text-black
                    {{ $qty <= $level ? 'bg-red-600' : 'bg-green-600' }}">
                    {{ $qty }} Adet
                </span>
            </div>
        </div>

    </div>

    {{-- ALT BÖLÜM --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

        {{-- Fiyat Grafiği --}}
        {{-- Fiyat Geçmişi Tablosu --}}
        <div class="bg-white rounded shadow p-4 mt-6">
            <h3 class="font-semibold mb-4">Fiyat Geçmişi Tablosu</h3>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border px-3 py-2 text-left">Tarih</th>
                    <th class="border px-3 py-2 text-left">Eski Fiyat</th>
                    <th class="border px-3 py-2 text-left">Yeni Fiyat</th>
                </tr>
                </thead>

                <tbody>
                @forelse($product->priceLogs as $log)
                    <tr>
                        <td class="border px-3 py-2">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y') }}
                        </td>
                        <td class="border px-3 py-2 text-gray-600">
                            ₺{{ number_format($log->old_price, 2, ',', '.') }}
                        </td>
                        <td class="border px-3 py-2 font-semibold text-gray-800">
                            ₺{{ number_format($log->new_price, 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-3 text-gray-500">
                            Fiyat geçmişi bulunamadı.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>


        {{-- Satışlar --}}
        <div class="bg-white p-5 rounded-lg shadow-md">
            <h3 class="font-semibold mb-4 text-gray-900">Son Satışlar</h3>
            <ul class="space-y-2">
                @forelse($recentSales as $sale)
                    <li class="flex justify-between items-center border-b pb-2 text-gray-800">
                        <span>{{ $sale->quantity }} adet</span>
                        <span class="font-semibold text-green-600">₺{{ number_format($sale->price, 2, ',', '.') }}</span>
                    </li>
                @empty
                    <p class="text-gray-500">Satış yapılmamış.</p>
                @endforelse
            </ul>
        </div>

        {{-- Satın Almalar --}}
        <div class="bg-white p-5 rounded-lg shadow-md">
            <h3 class="font-semibold mb-4 text-gray-900">Son Satın Almalar</h3>
            <ul class="space-y-2">
                @forelse($recentPurchases as $item)
                    <li class="flex justify-between items-center border-b pb-2 text-gray-800">
                        <span>{{ $item->quantity }} adet</span>
                        <span class="font-semibold text-blue-600">₺{{ number_format($item->price, 2, ',', '.') }}</span>
                    </li>
                @empty
                    <p class="text-gray-500">Satın alma yapılmamış.</p>
                @endforelse
            </ul>
        </div>

    </div>


    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @if(count($priceValues) > 0)
        <script>
            new Chart(document.getElementById('priceChart'), {
                type: 'line',
                data: {
                    labels: @json($priceDates),
                    datasets: [{
                        label: 'Fiyat (₺)',
                        data: @json($priceValues),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.25)',
                        borderWidth: 2,
                        pointRadius: 4,
                        tension: .4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    aspectRatio: 2,
                }
            });
        </script>
    @endif

</x-app-layout>
