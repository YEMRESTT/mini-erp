<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Satış Siparişleri
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Sipariş Listesi</h3>

            <a href="{{ route('sales.create') }}"
               class="bg-blue-600 text-black px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                + Yeni Sipariş
            </a>
        </div>

        @if(session('success'))
            <div class="p-3 bg-green-50 text-green-700 border border-green-300 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <table class="w-full text-sm text-gray-700 border">
                <thead>
                <tr class="bg-gray-100 text-center">
                    <th class="p-2">#</th>
                    <th class="p-2">Müşteri</th>
                    <th class="p-2">Durum</th>
                    <th class="p-2">Toplam</th>
                    <th class="p-2">Tarih</th>
                    <th class="p-2">Detay</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr class="text-center border-t">
                        <td class="p-2">{{ $order->id }}</td>
                        <td class="p-2">{{ $order->customer?->name ?? '---' }}</td>
                        <td class="p-2">{{ $order->status }}</td>
                        <td class="p-2">₺{{ number_format($order->total,2,',','.') }}</td>
                        <td class="p-2">{{ $order->created_at->format('d.m.Y H:i') }}</td>

                        <td class="p-2">
                            <a href="{{ route('sales.show', $order->id) }}"
                               class="text-blue-600 hover:underline">
                                Detay
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>

        @else
            <p class="text-gray-500 text-sm">Henüz satış kaydı yok.</p>
        @endif

    </div>

</x-app-layout>
