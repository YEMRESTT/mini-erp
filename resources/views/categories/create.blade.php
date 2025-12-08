<x-app-layout>
    <x-slot name="header" >
        <h2 class="text-xl font-bold text-gray-800">
            Yeni Kategori Ekle
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-6 px-4">

        @if ($errors->any())
            <div class="bg-red-200 border border-red-500 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST"
              class="bg-white p-6 rounded shadow">
            @csrf

            <label class="block mb-2 font-medium text-gray-700">
                Kategori Adı
            </label>
            <input type="text" name="name"
                   class="w-full border rounded p-2 mb-4"
                   placeholder="Örn: Elektronik" required>

            <div class="flex justify-end gap-3">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 border rounded">
                    Geri
                </a>
                <button class="px-4 py-2 bg-blue-600 text-black rounded hover:bg-blue-700">
                    Kaydet
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
