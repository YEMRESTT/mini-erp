<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            Tedarikçi Detayı – {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 space-y-6">

        {{-- ÜST BİLGİ --}}
        <div class="bg-white p-6 rounded shadow grid grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-500">Toplam Sipariş</p>
                <p class="text-lg font-semibold">{{ $totalOrders }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Toplam Alım (Completed)</p>
                <p class="text-lg font-semibold text-green-600">
                    ₺{{ number_format($totalSpent, 2, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Kayıt Tarihi</p>
                <p class="text-lg">
                    {{ $supplier->created_at->format('d.m.Y') }}
                </p>
            </div>
        </div>

        {{-- SİPARİŞLER --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Satın Alma Siparişleri</h3>

            @if($supplier->purchaseOrders->count())
                <table class="w-full text-sm border">
                    <thead class="bg-gray-100 text-center">
                    <tr>
                        <th class="p-2">#</th>
                        <th class="p-2">Durum</th>
                        <th class="p-2">Toplam</th>
                        <th class="p-2">Tarih</th>
                        <th class="p-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($supplier->purchaseOrders as $order)
                        <tr class="text-center border-t">
                            <td class="p-2">{{ $order->id }}</td>
                            <td class="p-2">{{ $order->status }}</td>
                            <td class="p-2">
                                ₺{{ number_format(
                                        $order->items->sum(fn($i)=>$i->price*$i->quantity),
                                        2, ',', '.'
                                    ) }}
                            </td>
                            <td class="p-2">
                                {{ $order->created_at->format('d.m.Y') }}
                            </td>
                            <td class="p-2">
                                <a href="{{ route('purchase.show', $order->id) }}"
                                   class="text-blue-600 text-sm">
                                    Detay
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-sm">
                    Bu tedarikçiyle henüz sipariş yok.
                </p>
            @endif
        </div>

    </div>
</x-app-layout>
