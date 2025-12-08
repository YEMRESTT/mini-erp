<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">Müşteriler</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4">

        <a href="{{ route('customers.create') }}"
           class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">
            + Yeni Müşteri
        </a>

        <table class="w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-50 border-b">
            <tr class="text-center text-sm font-semibold text-gray-700">
                <th class="py-3">ID</th>
                <th class="py-3">Ad Soyad</th>
                <th class="py-3">Email</th>
                <th class="py-3">Telefon</th>
                <th class="py-3">İşlemler</th>
            </tr>
            </thead>

            <tbody class="text-center">
            @foreach($customers as $c)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 font-medium">{{ $c->id }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->phone ?? '—' }}</td>

                    <td class="flex justify-center gap-2 py-2">
                        <a href="{{ route('customers.edit', $c->id) }}"
                           class="px-3 py-1 bg-yellow-500 text-black rounded text-xs hover:bg-yellow-600">
                            Düzenle
                        </a>

                        <a href="{{ route('customers.show', $c->id) }}"
                           class="px-3 py-1 bg-blue-600 text-black rounded text-xs hover:bg-blue-700 transition">
                            Detay
                        </a>


                        <form method="POST"
                              action="{{ route('customers.destroy', $c->id) }}"
                              onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button
                                class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
                                Sil
                            </button>
                        </form>


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>

    </div>
</x-app-layout>
