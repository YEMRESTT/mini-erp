<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Yeni Satış Siparişi</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow max-w-4xl mx-auto space-y-4">

        {{-- Müşteri seçimi --}}
        <select id="customer_id" class="w-full border rounded p-2">
            <option value="">--- Müşteri Seç ---</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>

        {{-- SEPET TABLOSU --}}
        <table class="w-full text-sm border">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2">Ürün</th>
                <th class="p-2">Adet</th>
                <th class="p-2">Fiyat</th>
                <th class="p-2">Sil</th>
            </tr>
            </thead>
            <tbody id="cart"></tbody>
        </table>

        <button id="add-product-btn"
                class="bg-teal-600 text-black px-4 py-2 rounded hover:bg-teal-700">
            Ürün Ekle
        </button>

        <p class="text-lg font-bold mt-3">
            Toplam: ₺<span id="total">0.00</span>
        </p>

        <form action="{{ route('sales.store') }}" method="POST" id="submitForm">
            @csrf
            <input type="hidden" name="customer_id" id="customerInput">
            <input type="hidden" name="items" id="itemsInput">
            <button type="submit"
                    class="bg-blue-700 text-black px-4 py-2 rounded hover:bg-blue-800 w-full">
                Kaydet
            </button>
        </form>
    </div>

    {{-- Modal --}}
    <div id="productModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-4 w-96 rounded shadow">
            <h3 class="text-lg font-semibold mb-3">Ürün Seç</h3>

            @foreach($products as $p)
                <button class="w-full text-left p-2 border rounded mb-2 productItem"
                        data-id="{{ $p->id }}"
                        data-name="{{ $p->name }}"
                        data-price="{{ $p->price }}">
                    {{ $p->name }} — ₺{{ number_format($p->price,2,',','.') }}
                </button>
            @endforeach

            <button onclick="closeModal()"
                    class="bg-red-500 text-black text-sm px-3 py-1 rounded mt-3">Kapat</button>
        </div>
    </div>

    <script>
        let items = [];
        const cart = document.getElementById('cart');
        const totalEl = document.getElementById('total');

        document.getElementById('add-product-btn').onclick = () =>
            document.getElementById('productModal').classList.remove('hidden');

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        document.querySelectorAll('.productItem').forEach(btn => {
            btn.onclick = () => {
                items.push({
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    price: parseFloat(btn.dataset.price),
                    quantity: 1
                });
                renderCart();
                closeModal();
            };
        });

        function renderCart() {
            cart.innerHTML = '';
            let total = 0;
            items.forEach((item, index) => {
                total += item.price * item.quantity;
                cart.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td><input type="number" min="1" value="${item.quantity}"
                            onchange="updateQuantity(${index}, this.value)" class="w-16 border rounded"></td>
                        <td>₺${item.price.toFixed(2)}</td>
                        <td><button onclick="removeItem(${index})" class="text-red-600">X</button></td>
                    </tr>
                `;
            });
            totalEl.innerText = total.toFixed(2);
        }

        function updateQuantity(i, val) {
            items[i].quantity = parseInt(val);
            renderCart();
        }

        function removeItem(i) {
            items.splice(i, 1);
            renderCart();
        }

        document.getElementById('submitForm').onsubmit = () => {
            document.getElementById('customerInput').value =
                document.getElementById('customer_id').value;
            document.getElementById('itemsInput').value = JSON.stringify(items);
        };
    </script>

</x-app-layout>
