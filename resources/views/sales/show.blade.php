<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Sipariş Detayı — #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 space-y-6">

        {{-- Müşteri Bilgisi --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold text-lg mb-3">Müşteri Bilgileri</h3>
            <p><strong>Ad:</strong> {{ $order->customer?->name }}</p>
            <p><strong>Email:</strong> {{ $order->customer?->email }}</p>
        </div>

        {{-- Ürünler --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold text-lg mb-3">Ürünler</h3>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100 text-center">
                <tr>
                    <th class="p-2">Ürün</th>
                    <th class="p-2">Adet</th>
                    <th class="p-2">Birim Fiyat</th>
                    <th class="p-2">Toplam</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr class="text-center border-t">
                        <td class="p-2">{{ $item->product->name }}</td>
                        <td class="p-2">{{ $item->quantity }}</td>
                        <td class="p-2">₺{{ number_format($item->price,2,',','.') }}</td>
                        <td class="p-2">₺{{ number_format($item->price * $item->quantity,2,',','.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p class="text-right font-bold mt-3">
                Toplam: ₺{{ number_format($order->total,2,',','.') }}
            </p>
        </div>

        {{-- Durum --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold text-lg mb-3">Sipariş Durumu</h3>
            <p class="mb-3">Şu an: <strong class="text-blue-700">{{ $order->status }}</strong></p>

            <form method="POST" action="{{ route('sales.update', $order->id) }}">
                @csrf
                @method('PUT')

                <select name="status" class="border rounded p-3">
                    <option {{ $order->status=='Pending'?'selected':'' }}>Pending</option>
                    <option {{ $order->status=='Approved'?'selected':'' }}>Approved</option>
                    <option {{ $order->status=='Completed'?'selected':'' }}>Completed</option>
                </select>

                <button class="bg-teal-600 text-black px-3 py-1 rounded ml-2 hover:bg-teal-700">
                    Güncelle
                </button>

            </form>

            <div class="flex justify-end pt-5">
                <a href="/sales"
                   class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition duration-300 ">
                    Geri
                </a>
            </div>

        </div>

    </div>

</x-app-layout>
