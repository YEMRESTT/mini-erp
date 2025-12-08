<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Kategoriler
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4">

        @if(session('success'))
            <div class="bg-green-500 text-black p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('categories.create') }}"
               class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
                Yeni Kategori Ekle
            </a>
        </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-sm text-gray-700">
                    <thead class="bg-blue-600 text-black text-center">
                    <tr>
                        <th class="p-3 font-semibold text-gray-800 text-center">ID</th>
                        <th class="p-3">Kategori Adı</th>
                        <th class="p-3 text-center">İşlemler</th>
                    </tr>
                    </thead>

                    <tbody class="text-center">
                    @forelse($categories as $cat)
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold text-gray-800 text-center">{{ $cat->id }}</td>
                            <td class="p-3 text-center">{{ $cat->name }}</td>

                            <td class="p-3 flex justify-center gap-2">

                                {{-- Düzenle --}}
                                <a href="{{ route('categories.edit', $cat->id) }}"
                                   class="px-3 py-1 text-xs rounded bg-yellow-400 hover:bg-yellow-500 text-black font-semibold transition">
                                    ✏️ Düzenle
                                </a>

                                {{-- Sil --}}
                                <form action="{{ route('categories.destroy', $cat->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Kategori silinsin mi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 text-xs rounded bg-red-500 hover:bg-red-600 text-white font-semibold transition">
                                        🗑 Sil
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-4">
                                Kategori bulunamadı 👀
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>


            <div class="mt-4">
            {{ $categories->links() }}
        </div>

    </div>
</x-app-layout>
