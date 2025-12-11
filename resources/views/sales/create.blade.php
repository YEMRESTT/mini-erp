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
            Ara Toplam: ₺<span id="subtotal">0.00</span>
        </p>
        <p class="text-sm mt-1">
            KDV (%20): ₺<span id="vat">0.00</span>
        </p>
        <p class="text-lg font-bold mt-1">
            Genel Toplam: ₺<span id="grandTotal">0.00</span>
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
    <div id="productModal" style="display: none;"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm
            flex items-center justify-center p-4 z-50">
        <div class="bg-white p-6 w-96 rounded shadow">

            <h3 class="text-lg font-semibold mb-4">Ürün Seç</h3>

            {{-- Arama --}}
            <input type="text" id="productSearch" placeholder="Ürün ara..."
                   class="w-full border rounded p-2 mb-4">

            {{-- Ürün Listesi --}}
            <div id="productList" class="space-y-2 mb-4">
                @foreach($products as $p)
                    <button class="w-full text-left p-3 border rounded productItem hover:bg-gray-100"
                            data-id="{{ $p->id }}"
                            data-name="{{ $p->name }}"
                            data-price="{{ $p->price }}">
                        <div class="font-medium">{{ $p->name }}</div>
                        <div class="text-sm text-gray-600">₺{{ number_format($p->price,2,',','.') }}</div>
                    </button>
                @endforeach
            </div>

            {{-- Sayfalama --}}
            <div class="flex items-center justify-between border-t pt-4">
                <button id="prevBtn" onclick="prevPage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    ← Önceki
                </button>
                <span id="pageInfo" class="text-sm font-medium">Sayfa 1</span>
                <button id="nextBtn" onclick="nextPage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sonraki →
                </button>
            </div>

            <button onclick="closeModal()"
                    class="bg-red-500 text-white text-sm px-4 py-2 rounded mt-4 w-full hover:bg-red-600">
                Kapat
            </button>
        </div>
    </div>

    <script>
        let items = [];
        const cart = document.getElementById('cart');
        const subtotalEl = document.getElementById('subtotal');
        const vatEl = document.getElementById('vat');
        const grandTotalEl = document.getElementById('grandTotal');
        const modal = document.getElementById('productModal');

        const VAT_RATE = 0.20; // %20

        // Sayfalama değişkenleri
        let currentPage = 1;
        const itemsPerPage = 5;
        let allProducts = [];
        let filteredProducts = [];

        // Ürünleri yükle
        document.querySelectorAll('.productItem').forEach(btn => {
            allProducts.push({
                element: btn,
                id: btn.dataset.id,
                name: btn.dataset.name.toLowerCase(),
                displayName: btn.dataset.name,
                price: btn.dataset.price
            });
        });

        // Modal açma
        document.getElementById('add-product-btn').onclick = () => {
            filteredProducts = [...allProducts];
            currentPage = 1;
            document.getElementById('productSearch').value = '';
            showPage();
            modal.style.display = 'flex';
        };

        // Modal kapatma
        function closeModal() {
            modal.style.display = 'none';
        }

        // Arama
        document.getElementById('productSearch').oninput = (e) => {
            const search = e.target.value.toLowerCase();
            filteredProducts = allProducts.filter(p => p.name.includes(search));
            currentPage = 1;
            showPage();
        };

        // Sayfa gösterme
        function showPage() {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageProducts = filteredProducts.slice(start, end);
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);

            // Tüm ürünleri gizle
            allProducts.forEach(p => p.element.style.display = 'none');

            // Sadece bu sayfadakileri göster
            pageProducts.forEach(p => p.element.style.display = 'block');

            // Sayfa bilgisi
            document.getElementById('pageInfo').innerText =
                `Sayfa ${currentPage} / ${totalPages || 1}`;

            // Butonları aktif/pasif yap
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage >= totalPages;
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                showPage();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                showPage();
            }
        }

        // Ürün seçimi
        allProducts.forEach(p => {
            p.element.onclick = () => {
                items.push({
                    id: p.id,
                    name: p.displayName,
                    price: parseFloat(p.price),
                    quantity: 1
                });
                renderCart();
                closeModal();
            };
        });

        // Sepet render
        function renderCart() {
            cart.innerHTML = '';
            let subtotal = 0;

            items.forEach((item, index) => {
                subtotal += item.price * item.quantity;
                cart.innerHTML += `
                    <tr>
                        <td class="p-2">${item.name}</td>
                        <td class="p-2">
                            <input type="number" min="1" value="${item.quantity}"
                                onchange="updateQuantity(${index}, this.value)"
                                class="w-16 border rounded text-center p-1">
                        </td>
                        <td class="p-2">₺${item.price.toFixed(2)}</td>
                        <td class="p-2">
                            <button type="button"
                                    onclick="removeItem(${index})"
                                    class="text-red-600 font-bold hover:text-red-800">
                                X
                            </button>
                        </td>
                    </tr>
                `;
            });

            const vat = subtotal * VAT_RATE;
            const grand = subtotal + vat;

            subtotalEl.innerText = subtotal.toFixed(2);
            vatEl.innerText = vat.toFixed(2);
            grandTotalEl.innerText = grand.toFixed(2);
        }

        function updateQuantity(i, val) {
            items[i].quantity = parseInt(val || 1);
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
