<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Kategori Düzenle — {{ $category->name }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4">

        <form action="{{ route('categories.update', $category->id) }}"
              method="POST"
              class="bg-white p-6 rounded shadow">

            @csrf
            @method('PUT')

            <label class="block mb-2 font-medium">Kategori Adı</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                   class="w-full border rounded p-2" required>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 border rounded text-gray-700">
                    Geri
                </a>
                <button class="px-4 py-2 bg-blue-600 text-black rounded hover:bg-blue-700">
                    Kaydet
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
