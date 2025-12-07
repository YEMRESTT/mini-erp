<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900">Bildirimler</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-gray-800 text-white text-xs uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">Başlık</th>
                    <th class="px-4 py-2 text-left">Mesaj</th>
                    <th class="px-4 py-2 text-center">Durum</th>
                    <th class="px-4 py-2 text-center">İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($notifications as $notification)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $notification->title }}</td>
                        <td class="px-4 py-2">{{ $notification->message }}</td>
                        <td class="px-4 py-2 text-center">
                            @if(!$notification->is_read)
                                <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">Okunmadı</span>
                            @else
                                <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Okundu</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button class="text-blue-600 hover:underline text-xs">
                                        Okundu İşaretle
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="p-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
