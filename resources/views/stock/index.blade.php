<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Stok Yönetimi
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4">

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-200 text-gray-700 uppercase text-xs font-bold">
                <tr>
                    <th class="px-4 py-3 text-left">Ürün</th>
                    <th class="px-4 py-3 text-left">Stok</th>
                    <th class="px-4 py-3 text-left">Minimum</th>
                    <th class="px-4 py-3 text-left">Durum</th>
                    <th class="px-4 py-3 text-center">İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($stocks as $stock)
                    @php
                        $qty = $stock->quantity;
                        $min = $stock->min_level;
                        $isCritical = $qty <= $min;
                    @endphp
                    <tr class="border-b">
                        <td class="px-4 py-3">
                            <strong>{{ $stock->product->name }}</strong><br>
                            <span class="text-xs text-gray-500">SKU: {{ $stock->product->sku }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $qty }}</td>
                        <td class="px-4 py-3">{{ $min }}</td>
                        <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-white text-xs
                                    {{ $isCritical ? 'bg-red-600' : 'bg-green-600' }}">
                                    {{ $isCritical ? 'Kritik' : 'Normal' }}
                                </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="openStockModal({{ $stock->id }}, 'in')"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                Giriş
                            </button>

                            <button onclick="openStockModal({{ $stock->id }}, 'out')"
                                    class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                Çıkış
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $stocks->links() }}
        </div>

    </div>

    {{-- Modal --}}
    <div id="stockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96 shadow">
            <h3 class="text-lg font-semibold mb-4">Stok Güncelle</h3>
            <form id="stockForm" method="POST">
                @csrf
                <input type="hidden" name="type" id="modalType">
                <input type="hidden" name="description" value="Manual update">

                <label class="block text-sm font-semibold mb-1">Miktar</label>
                <input type="number" name="quantity"
                       class="w-full border rounded px-2 py-1 mb-4" required>

                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="closeStockModal()"
                            class="px-3 py-1 bg-gray-400 text-white rounded">
                        İptal
                    </button>
                    <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal JS --}}
    <script>
        function openStockModal(id, type) {
            document.getElementById('modalType').value = type;
            document.getElementById('stockForm').action = '/stock/' + id + '/update';

            document.getElementById('stockModal').classList.remove('hidden');
            document.getElementById('stockModal').classList.add('flex');
        }

        function closeStockModal() {
            document.getElementById('stockModal').classList.add('hidden');
        }
    </script>

</x-app-layout>
