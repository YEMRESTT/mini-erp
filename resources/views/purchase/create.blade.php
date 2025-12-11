<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Yeni Satın Alma Siparişi</h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow max-w-4xl mx-auto space-y-4">

        {{-- TEDARİKÇİ SEÇİMİ --}}
        <button type="button" id="selectSupplierBtn"
                class="bg-gray-700 text-black px-4 py-2 rounded hover:bg-gray-800 w-full">
            Tedarikçi Seç
        </button>

        <p id="selectedSupplier" class="mt-2 text-gray-700 font-semibold hidden"></p>

        {{-- SEPET TABLOSU --}}
        <table class="w-full text-sm border mt-4">
            <thead>
            <tr class="bg-gray-100 text-center">
                <th class="p-2">Ürün</th>
                <th class="p-2">Adet</th>
                <th class="p-2">Fiyat</th>
                <th class="p-2">Toplam</th>
                <th class="p-2">Sil</th>
            </tr>
            </thead>
            <tbody id="cart"></tbody>
        </table>

        <button type="button" id="add-product-btn"
                class="bg-teal-600 text-black px-4 py-2 rounded hover:bg-teal-700">
            Ürün Ekle
        </button>

        {{-- TOPLAM --}}
        <div class="mt-3">
            <p class="text-lg font-bold">Genel Toplam: ₺<span id="grandTotal">0.00</span></p>
        </div>

        <form action="{{ route('purchase.store') }}" method="POST" id="submitForm">
            @csrf
            <input type="hidden" name="supplier_id" id="supplier_id">
            <input type="hidden" name="items" id="itemsInput">
            <button type="submit"
                    class="bg-blue-700 text-black px-4 py-2 rounded hover:bg-blue-800 w-full">
                Kaydet
            </button>
        </form>
    </div>

    {{-- MODAL: TEDARİKÇİ --}}
    <div id="supplierModal" style="display: none;"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-black p-6 w-96 rounded shadow text-black">
            <h3 class="text-lg font-semibold mb-4 ">Tedarikçi Seç</h3>

            {{-- Arama --}}
            <input type="text" id="supplierSearch" placeholder="Tedarikçi ara..."
                   class="w-full border rounded p-2 mb-4">

            {{-- Tedarikçi Listesi --}}
            <div id="supplierList" class="space-y-2 mb-4">
                @foreach($suppliers as $s)
                    <button type="button" class="w-full text-left p-3 border rounded supplierItem hover:bg-gray-100"
                            data-id="{{ $s->id }}"
                            data-name="{{ $s->name }}">
                        <div class="font-medium">{{ $s->name }}</div>
                    </button>
                @endforeach
            </div>

            {{-- Sayfalama --}}
            <div class="flex items-center justify-between border-t pt-4">
                <button id="supplierPrevBtn" onclick="supplierPrevPage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    ← Önceki
                </button>
                <span id="supplierPageInfo" class="text-sm font-medium">Sayfa 1</span>
                <button id="supplierNextBtn" onclick="supplierNextPage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sonraki →
                </button>
            </div>

            <button type="button" onclick="closeSupplierModal()"
                    class="bg-red-500 text-white text-sm px-4 py-2 rounded mt-4 w-full hover:bg-red-600">
                Kapat
            </button>
        </div>
    </div>

    {{-- MODAL: ÜRÜN --}}
    <div id="productModal" style="display: none;"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white p-6 w-96 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Ürün Seç</h3>

            {{-- Arama --}}
            <input type="text" id="productSearch" placeholder="Ürün ara..."
                   class="w-full border rounded p-2 mb-4">

            {{-- Ürün Listesi --}}
            <div id="productList" class="space-y-2 mb-4">
                @foreach($products as $p)
                    <button type="button" class="w-full text-left p-3 border rounded productItem hover:bg-gray-100"
                            data-id="{{ $p->id }}"
                            data-name="{{ $p->name }}">
                        <div class="font-medium">{{ $p->name }}</div>
                    </button>
                @endforeach
            </div>

            {{-- Sayfalama --}}
            <div class="flex items-center justify-between border-t pt-4">
                <button id="productPrevBtn" onclick="productPrevPage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    ← Önceki
                </button>
                <span id="productPageInfo" class="text-sm font-medium">Sayfa 1</span>
                <button id="productNextBtn" onclick="productNextPage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sonraki →
                </button>
            </div>

            <button type="button" onclick="closeProductModal()"
                    class="bg-red-500 text-white text-sm px-4 py-2 rounded mt-4 w-full hover:bg-red-600">
                Kapat
            </button>
        </div>
    </div>

    <script>
        const supplierModal = document.getElementById('supplierModal');
        const productModal = document.getElementById('productModal');
        const selectedSupplier = document.getElementById('selectedSupplier');
        const supplier_id = document.getElementById('supplier_id');
        const grandTotalEl = document.getElementById('grandTotal');
        const itemsInput = document.getElementById('itemsInput');

        // Sayfalama değişkenleri
        const itemsPerPage = 5;

        // TEDARİKÇİ
        let supplierCurrentPage = 1;
        let allSuppliers = [];
        let filteredSuppliers = [];

        // ÜRÜN
        let productCurrentPage = 1;
        let allProducts = [];
        let filteredProducts = [];
        let items = [];
        const cart = document.getElementById('cart');

        // TEDARİKÇİ - Yükleme
        document.querySelectorAll('.supplierItem').forEach(btn => {
            allSuppliers.push({
                element: btn,
                id: btn.dataset.id,
                name: btn.dataset.name.toLowerCase(),
                displayName: btn.dataset.name
            });
        });

        // TEDARİKÇİ - Modal açma
        document.getElementById('selectSupplierBtn').onclick = () => {
            filteredSuppliers = [...allSuppliers];
            supplierCurrentPage = 1;
            document.getElementById('supplierSearch').value = '';
            showSupplierPage();
            supplierModal.style.display = 'flex';
        };

        function closeSupplierModal() {
            supplierModal.style.display = 'none';
        }

        // TEDARİKÇİ - Arama
        document.getElementById('supplierSearch').oninput = (e) => {
            const search = e.target.value.toLowerCase();
            filteredSuppliers = allSuppliers.filter(s => s.name.includes(search));
            supplierCurrentPage = 1;
            showSupplierPage();
        };

        // TEDARİKÇİ - Sayfa gösterme
        function showSupplierPage() {
            const start = (supplierCurrentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageSuppliers = filteredSuppliers.slice(start, end);
            const totalPages = Math.ceil(filteredSuppliers.length / itemsPerPage);

            allSuppliers.forEach(s => s.element.style.display = 'none');
            pageSuppliers.forEach(s => s.element.style.display = 'block');

            document.getElementById('supplierPageInfo').innerText = `Sayfa ${supplierCurrentPage} / ${totalPages || 1}`;
            document.getElementById('supplierPrevBtn').disabled = supplierCurrentPage === 1;
            document.getElementById('supplierNextBtn').disabled = supplierCurrentPage >= totalPages;
        }

        function supplierPrevPage() {
            if (supplierCurrentPage > 1) {
                supplierCurrentPage--;
                showSupplierPage();
            }
        }

        function supplierNextPage() {
            const totalPages = Math.ceil(filteredSuppliers.length / itemsPerPage);
            if (supplierCurrentPage < totalPages) {
                supplierCurrentPage++;
                showSupplierPage();
            }
        }

        // TEDARİKÇİ - Seçim
        allSuppliers.forEach(s => {
            s.element.onclick = () => {
                supplier_id.value = s.id;
                selectedSupplier.innerText = "Seçilen Tedarikçi: " + s.displayName;
                selectedSupplier.classList.remove('hidden');
                closeSupplierModal();
            };
        });

        // ÜRÜN - Yükleme
        document.querySelectorAll('.productItem').forEach(btn => {
            allProducts.push({
                element: btn,
                id: btn.dataset.id,
                name: btn.dataset.name.toLowerCase(),
                displayName: btn.dataset.name
            });
        });

        // ÜRÜN - Modal açma
        document.getElementById('add-product-btn').onclick = () => {
            filteredProducts = [...allProducts];
            productCurrentPage = 1;
            document.getElementById('productSearch').value = '';
            showProductPage();
            productModal.style.display = 'flex';
        };

        function closeProductModal() {
            productModal.style.display = 'none';
        }

        // ÜRÜN - Arama
        document.getElementById('productSearch').oninput = (e) => {
            const search = e.target.value.toLowerCase();
            filteredProducts = allProducts.filter(p => p.name.includes(search));
            productCurrentPage = 1;
            showProductPage();
        };

        // ÜRÜN - Sayfa gösterme
        function showProductPage() {
            const start = (productCurrentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageProducts = filteredProducts.slice(start, end);
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);

            allProducts.forEach(p => p.element.style.display = 'none');
            pageProducts.forEach(p => p.element.style.display = 'block');

            document.getElementById('productPageInfo').innerText = `Sayfa ${productCurrentPage} / ${totalPages || 1}`;
            document.getElementById('productPrevBtn').disabled = productCurrentPage === 1;
            document.getElementById('productNextBtn').disabled = productCurrentPage >= totalPages;
        }

        function productPrevPage() {
            if (productCurrentPage > 1) {
                productCurrentPage--;
                showProductPage();
            }
        }

        function productNextPage() {
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            if (productCurrentPage < totalPages) {
                productCurrentPage++;
                showProductPage();
            }
        }

        // ÜRÜN - Seçim
        allProducts.forEach(p => {
            p.element.onclick = () => {
                items.push({
                    id: p.id,
                    name: p.displayName,
                    quantity: 1,
                    price: 0
                });
                renderCart();
                closeProductModal();
            };
        });

        // SEPET RENDER
        function renderCart() {
            cart.innerHTML = '';
            let total = 0;

            items.forEach((item, index) => {
                let lineTotal = item.price * item.quantity;
                total += lineTotal;

                cart.innerHTML += `
                    <tr class="text-center border-t">
                        <td class="p-2">${item.name}</td>
                        <td class="p-2">
                            <input type="number" min="1" value="${item.quantity}"
                                onchange="updateQty(${index}, this.value)"
                                class="w-16 border rounded p-1">
                        </td>
                        <td class="p-2">
                            <input type="number" min="0" step="0.01" value="${item.price}"
                                onchange="updatePrice(${index}, this.value)"
                                class="w-24 border rounded p-1">
                        </td>
                        <td class="p-2">₺${lineTotal.toFixed(2)}</td>
                        <td class="p-2">
                            <button type="button" onclick="removeItem(${index})" class="text-red-600 hover:text-red-800 font-bold">X</button>
                        </td>
                    </tr>
                `;
            });

            grandTotalEl.innerText = total.toFixed(2);
        }

        function updateQty(i, val) {
            items[i].quantity = parseInt(val || 1);
            renderCart();
        }

        function updatePrice(i, val) {
            items[i].price = parseFloat(val || 0);
            renderCart();
        }

        function removeItem(i) {
            items.splice(i, 1);
            renderCart();
        }

        // FORM SUBMIT
        document.getElementById('submitForm').addEventListener('submit', function (e) {
            if (!supplier_id.value) {
                e.preventDefault();
                alert('Tedarikçi seçmelisin.');
                return;
            }

            if (items.length === 0) {
                e.preventDefault();
                alert('En az bir ürün eklemelisin.');
                return;
            }

            itemsInput.value = JSON.stringify(items);
        });
    </script>
</x-app-layout>
