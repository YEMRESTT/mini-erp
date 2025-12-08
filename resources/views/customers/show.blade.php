<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Müşteri Detayı — {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 space-y-6">

        {{-- 🧑 Temel Bilgiler --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Müşteri Bilgileri</h3>

            <div class="grid grid-cols-2 gap-4 text-gray-700">
                <p><span class="font-semibold">E-posta:</span> {{ $customer->email }}</p>
                <p><span class="font-semibold">Telefon:</span> {{ $customer->phone ?? '—' }}</p>
                <p class="col-span-2"><span class="font-semibold">Adres:</span> {{ $customer->address ?? '—' }}</p>
            </div>

            <div class="mt-4 pt-4 border-t">
                <span class="font-semibold text-green-600">Toplam Harcama:</span>
                <span class="text-lg font-bold">
                    ₺{{ number_format($totalSpent, 2, ',', '.') }}
                </span>
            </div>

            @if($lastOrder)
                <p class="mt-2 text-sm text-gray-500">
                    Son Sipariş: {{ $lastOrder->created_at->format('d.m.Y H:i') }}
                </p>
            @endif
        </div>

        {{-- 📝 Müşteri Notları --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Müşteri Notları</h3>

            {{-- Not ekleme --}}
            <form action="{{ route('customers.notes.store', $customer->id) }}" method="POST" class="space-y-2 mb-4">
                @csrf
                <textarea name="note" rows="3" class="w-full border rounded p-2 text-sm"
                          placeholder="Yeni not yaz..."></textarea>

                <button type="submit"
                        class="px-3 py-1 text-sm bg-blue-600 text-black rounded hover:bg-blue-700">
                    Not Ekle
                </button>
            </form>

            {{-- Not Listesi --}}
            @forelse($customer->notes as $note)
                <div class="p-3 border rounded mb-3 bg-gray-50">

                    <form action="{{ route('customers.notes.update', $note->id) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        @method('PATCH')

                        <input type="text" name="note"
                               value="{{ $note->note }}"
                               class="border p-2 rounded flex-1 text-sm">

                        <button class="px-3 py-1 bg-yellow-500 text-xs rounded hover:bg-yellow-600 text-black">
                            Güncelle
                        </button>
                    </form>

                    <div class="flex justify-between items-center mt-2">
                        <small class="text-xs text-gray-500">
                            {{ $note->created_at->format('d.m.Y H:i') }}
                        </small>

                        <form action="{{ route('customers.notes.delete', $note->id) }}"
                              method="POST" onsubmit="return confirm('Not silinsin mi?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-xs rounded hover:bg-red-700 text-white">
                                Sil
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Henüz not yok.</p>
            @endforelse
        </div>

        {{-- 🛒 Sipariş Geçmişi --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Sipariş Geçmişi</h3>

            @if($customer->salesOrders->count())
                <table class="w-full text-sm text-gray-700">
                    <thead>
                    <tr class="bg-gray-100 text-center">
                        <th class="p-2">Sipariş #</th>
                        <th class="p-2">Durum</th>
                        <th class="p-2">Toplam</th>
                        <th class="p-2">Tarih</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customer->salesOrders as $order)
                        <tr class="text-center border-b">
                            <td class="p-2">{{ $order->id }}</td>
                            <td class="p-2">{{ $order->status }}</td>
                            <td class="p-2">₺{{ number_format($order->total, 2, ',', '.') }}</td>
                            <td class="p-2">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-sm">Sipariş bulunmuyor.</p>
            @endif
        </div>

    </div>

</x-app-layout>
