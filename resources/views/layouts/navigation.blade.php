<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Sol Taraf: Logo + Menü --}}
            <div class="flex items-center gap-8">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <h2 class="text-xl font-bold text-gray-800">{{ config('app.name', 'ERP Sistem') }}</h2>
                </a>

                {{-- Menü Linkleri (Desktop) --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('dashboard')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('products.*')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Ürünler
                    </a>

                    <a href="{{ route('stock.index') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('stock.*')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Stok Yönetimi
                    </a>

                    <a href="{{ route('notifications.index') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('notifications.*')
                                 ? 'bg-blue-50 text-blue-700'
                                 : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Bildirimler
                    </a>
                </div>
            </div>

            {{-- Sağ Taraf: Profil Menüsü --}}
            <div class="hidden md:flex items-center gap-3">
                {{-- Kullanıcı Adı --}}
                <span class="text-sm font-medium text-gray-700">
                    {{ Auth::user()->name }}
                </span>

                {{-- Profil Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profilim
                        </x-dropdown-link>

                        <div class="border-t border-gray-200"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();"
                                             class="text-red-600">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Çıkış Yap
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger Menü (Mobil) --}}
            <div class="md:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobil Menü --}}
    <div x-show="open" @click.away="open = false" class="md:hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('products.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('products.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Ürünler
            </a>

            <a href="{{ route('stock.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('stock.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Stok Yönetimi
            </a>

            <a href="{{ route('notifications.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('notifications.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Bildirimler
            </a>

            <div class="border-t border-gray-200 my-2"></div>

            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700">
                Profilim
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600">
                    Çıkış Yap
                </button>
            </form>
        </div>
    </div>

</nav>
