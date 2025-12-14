<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Fatura #{{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4 space-y-6">

        {{-- Üst Bilgi --}}
        <div class="bg-white p-6 rounded shadow space-y-3">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Müşteri</p>
                    <p class="text-lg font-semibold">
                        {{ $invoice->order->customer->name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        E-posta: {{ $invoice->order->customer->email }}
                    </p>
                    <p class="text-xs text-gray-500">
                        Tarih: {{ $invoice->created_at->format('d.m.Y H:i') }}
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-sm text-gray-500">Durum</p>
                    <p class="px-3 py-1 bg-blue-100 rounded text-blue-700 font-semibold inline-block">
                        {{ $invoice->status }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Fatura Kalemleri --}}
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Fatura Kalemleri</h3>

            @if($invoice->items->count())
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
                    @foreach($invoice->items as $item)
                        <tr class="text-center border-t">
                            <td class="p-2">
                                {{ $item->product->name ?? '—' }}
                            </td>
                            <td class="p-2">{{ $item->quantity }}</td>
                            <td class="p-2">
                                ₺{{ number_format($item->price, 2, ',', '.') }}
                            </td>
                            <td class="p-2">
                                ₺{{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">Fatura kalemi bulunamadı.</p>
            @endif
        </div>

        {{-- Toplamlar --}}
        <div class="bg-white p-6 rounded shadow grid grid-cols-3 gap-4 text-sm">
            {{-- Ara Toplam / KDV / Genel Toplam Kartları --}}
            <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                <div class="p-3 bg-gray-50 rounded border">
                    <p class="text-gray-500">Ara Toplam</p>
                    <p class="text-lg font-semibold">
                        ₺{{ number_format($subtotal, 2, ',', '.') }}
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded border">
                    <p class="text-gray-500">KDV (%20)</p>
                    <p class="text-lg font-semibold">
                        ₺{{ number_format($vatAmount, 2, ',', '.') }}
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded border">
                    <p class="text-gray-500">Genel Toplam</p>
                    <p class="text-lg font-semibold text-green-700">
                        ₺{{ number_format($grandTotal, 2, ',', '.') }}
                    </p>
                </div>

                <a href="{{ route('invoices.pdf', $invoice->id) }}"
                   class="inline-block mt-4 bg-red-600 text-white px-4 py-2 rounded
          hover:bg-red-700">
                    PDF Olarak İndir
                </a>


                <div class="flex gap-3 mt-4">

                    {{-- PDF GÖRÜNTÜLE --}}
                    <a href="{{ route('invoices.pdf.view', $invoice->id) }}"
                       target="_blank"
                       class="bg-gray-700 text-black px-4 py-2 rounded
              hover:bg-gray-800">
                        PDF Görüntüle
                    </a>


                </div>


            </div>

        </div>

    </div>
</x-app-layout>
