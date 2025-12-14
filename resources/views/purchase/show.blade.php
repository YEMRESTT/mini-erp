<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Satın Alma Siparişi #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4 space-y-6">

        {{-- ÜST KART  --}}
        <div class="bg-white p-6 rounded shadow space-y-3">
            <div class="flex justify-between items-start">

                {{-- TEDARİKÇİ --}}
                <div>
                    <p class="text-sm text-gray-500">Tedarikçi</p>
                    <p class="text-lg font-semibold">
                        {{ $order->supplier?->name ?? '—' }}
                    </p>

                    <a href="{{ route('suppliers.show',  $order->supplier->id) }}"
                       class="text-blue-600 hover:underline">
                        Detay
                    </a>


                    <p class="text-xs text-gray-500 mt-1">
                        Tarih: {{ $order->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>

                {{-- DURUM --}}
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-1">Durum</p>

                    <form action="{{ route('purchase.update', $order->id) }}"
                          method="POST"
                          class="flex items-center gap-2">
                        @csrf
                        @method('PUT')

                        <select name="status" class="border rounded px-2 py-1 text-sm">
                            @foreach(['Pending','Approved','Completed'] as $status)
                                <option value="{{ $status }}"
                                    {{ $order->status === $status ? 'selected' : '' }}
                                    {{ $order->status === 'Completed' ? 'disabled' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>


                        <button class="bg-blue-600 text-blak text-xs px-3 py-1 rounded hover:bg-blue-700">
                            Güncelle
                        </button>
                    </form>
                </div>
            </div>

            {{-- TUTARLAR --}}
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="p-3 bg-gray-50 rounded border">
                    <p class="text-gray-500">Ara Toplam</p>
                    <p class="text-lg font-semibold">
                        ₺{{ number_format($subtotal, 2, ',', '.') }}
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded border">
                    <p class="text-gray-500">Genel Toplam</p>
                    <p class="text-lg font-semibold text-green-700">
                        ₺{{ number_format($total, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>


        {{-- SATIRLAR --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Sipariş Kalemleri</h3>

            @if($order->items->count())
                <table class="w-full text-sm text-gray-700 border">
                    <thead>
                    <tr class="bg-gray-100 text-center">
                        <th class="p-2">Ürün</th>
                        <th class="p-2">Adet</th>
                        <th class="p-2">Birim Fiyat</th>
                        <th class="p-2">Toplam</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($order->items as $item)
                        <tr class="text-center border-t">
                            <td class="p-2">{{ $item->product?->name ?? '—' }}</td>

                            <td class="p-2">{{ $item->quantity }}</td>

                            <td class="p-2">
                                ₺{{ number_format($item->price, 2, ',', '.') }}
                            </td>

                            <td class="p-2 font-semibold">
                                ₺{{ number_format($item->quantity * $item->price, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            @else
                <p class="text-gray-500 text-sm">Bu siparişte ürün yok.</p>
            @endif
        </div>

        {{-- LOG KAYITLARI --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Tarihçe</h3>

            @if($order->logs->count())
                <ul class="space-y-2 text-sm text-gray-700">
                    @foreach($order->logs as $log)
                        <li class="border rounded p-2 flex justify-between">
                            <span>{{ $log->action }}</span>
                            <span class="text-xs text-gray-500">
                                {{ $log->created_at->format('d.m.Y H:i') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm">Henüz log kaydı yok.</p>
            @endif
        </div>

    </div>
</x-app-layout>
