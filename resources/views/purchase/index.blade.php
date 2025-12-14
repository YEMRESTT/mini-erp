<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Satın Alma Siparişleri
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Sipariş Listesi</h3>

            <a href="{{ route('purchase.create') }}"
               class="bg-blue-600 text-black px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                + Yeni Sipariş
            </a>
        </div>

        {{-- Başarılı mesaj --}}
        @if(session('success'))
            <div class="p-3 bg-green-50 text-green-700 border border-green-300 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <table class="w-full text-sm text-gray-700 border rounded">
                <thead>
                <tr class="bg-gray-100 text-center">
                    <th class="p-2">#</th>
                    <th class="p-2">Tedarikçi</th>
                    <th class="p-2">Tarih</th>
                    <th class="p-2">Toplam</th>
                    <th class="p-2">Durum</th>
                    <th class="p-2">İşlemler</th>
                </tr>
                </thead>


                <tbody>
                @foreach($orders as $order)
                    <tr class="text-center border-t">

                        <td class="p-2">#{{ $order->id }}</td>

                        <td class="p-2">
                            {{ $order->supplier?->name ?? '—' }}
                        </td>

                        <td class="p-2">
                            {{ $order->created_at->format('d.m.Y') }}
                        </td>

                        {{-- 🔥 DOĞRU TOPLAM --}}
                        <td class="p-2 font-semibold">
                            ₺{{ number_format($order->calculated_total, 2, ',', '.') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="p-2">
                            @php
                                $colors = [
                                    'Pending'   => 'bg-yellow-100 text-yellow-800',
                                    'Approved'  => 'bg-blue-100 text-blue-800',
                                    'Completed' => 'bg-green-100 text-green-800',
                                ];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                                {{ $order->status }}
                            </span>
                        </td>

                        {{-- AKSİYONLAR --}}
                        <td class="p-2 flex justify-center gap-2">
                            <a href="{{ route('purchase.show', $order->id) }}"
                               class="bg-blue-600 text-black text-xs px-3 py-1 rounded hover:bg-blue-700">
                                Detay
                            </a>

                            @if($order->status !== 'Completed')
                                <form action="{{ route('purchase.destroy', $order->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Silmek istediğine emin misin?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600 hover:underline text-sm">
                                        Sil
                                    </button>
                                </form>
                            @endif

                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>

            {{-- SAYFALAMA --}}
            <div class="mt-4">
                {{ $orders->links() }}
            </div>

        @else
            <p class="text-gray-500 text-sm">Henüz satın alma siparişi oluşturulmamış.</p>
        @endif

    </div>
</x-app-layout>
