<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 ">

        {{-- Toplam Satış --}}
        <div class="p-5 bg-gradient-to-br from-blue-500 to-blue-700 text-black rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg">Toplam Satış</h3>
                🧾
            </div>
            <p class="text-3xl font-bold mt-3">₺{{ number_format($totalSales, 2, ',', '.') }}</p>
        </div>

        {{-- Bu Ay Gelir --}}
        <div class="p-5 bg-gradient-to-br from-green-500 to-green-700 text-black rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg">Bu Ay Gelir</h3>
                💸
            </div>
            <p class="text-3xl font-bold mt-3">₺{{ number_format($monthlyRevenue, 2, ',', '.') }}</p>
        </div>

        {{-- En Çok Satan Ürün --}}
        <div class="p-5 bg-gradient-to-br from-purple-500 to-purple-700 text-black rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg">Popüler Ürün</h3>
                ⭐
            </div>
            <p class="text-xl font-bold mt-3">
                {{ $topProduct?->product?->name ?? 'Ürün Yok' }}
            </p>
        </div>

        {{-- Kritik Stok --}}
        <div class="p-5 bg-gradient-to-br from-red-500 to-red-700 text-black rounded-xl shadow-lg transform hover:scale-105 transition animate-pulse">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg">Kritik Stok</h3>
                🚨
            </div>
            <p class="text-3xl font-bold mt-3">{{ $criticalStockCount }}</p>
        </div>

        {{-- Haftalık Satış Grafiği --}}
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Haftalık Satış Grafiği</h3>
            <canvas id="weeklySalesChart" height="100"></canvas>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('weeklySalesChart').getContext('2d');
        var weeklySalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($days),
                datasets: [{
                    label: '₺ Satış (Son 7 Gün)',
                    data: @json($totals),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#2563eb',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 900,
                    easing: 'easeOutQuart'
                },
                scales: {
                    y: {
                        ticks: {
                            callback: value => '₺ ' + value
                        }
                    }
                }
            }
        });
    </script>


</x-app-layout>
