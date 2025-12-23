@php
    $menus = [
        [
            'header' => 'GENERAL',
            'items' => [
                [
                    'title' => 'Dashboard',
                    'route' => route('dashboard'),
                    'icon' => 'layout-dashboard',
                    'active' => request()->routeIs('dashboard'),
                ],
            ],
        ],
        [
            'header' => 'MASTER DATA',
            'items' => [
                [
                    'title' => 'Tipe Akun',
                    'route' => route('tipe_akun'),
                    'icon' => 'tags',
                    'active' => request()->routeIs('tipe_akun*'), // Aktif jika URL diawali tipe_akun
                ],
                [
                    'title' => 'Daftar Akun (COA)',
                    'route' => route('akun'),
                    'icon' => 'library',
                    'active' => request()->routeIs('akun*'),
                ],
            ],
        ],
        [
            'header' => 'TRANSAKSI',
            'items' => [
                [
                    'title' => 'Jurnal Umum',
                    'route' => route('jurnal_umum'),
                    'icon' => 'file-pen-line',
                    'active' => request()->routeIs('jurnal_umum*'),
                ],
                [
                    'title' => 'Riwayat Jurnal',
                    'route' => route('riwayat_jurnal'),
                    'icon' => 'history',
                    'active' => request()->routeIs('riwayat_jurnal*'),
                ],
            ],
        ],
    ];
@endphp

<div class="drawer-side z-50">
    <label for="aside-dashboard" aria-label="close sidebar" class="drawer-overlay"></label>

    <aside
        class="bg-base-100 min-h-full w-64 lg:w-72 border-r border-base-200 flex flex-col transition-all duration-300">

        <div
            class="h-16 flex items-center gap-3 px-6 border-b border-base-200 bg-base-100/50 backdrop-blur sticky top-0 z-20">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-primary-content shadow-sm">
                <x-lucide-calculator class="w-5 h-5" />
            </div>
            <div>
                <span class="block text-lg font-bold tracking-tight text-base-content leading-tight">AccSystem</span>
                <span class="text-[10px] uppercase font-semibold text-base-content/50 tracking-wider">Finance &
                    Book</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 custom-scrollbar">
            <ul class="menu menu-md gap-1">
                @foreach ($menus as $section)

                    <li class="menu-title text-xs font-bold text-base-content/40 mt-2 mb-1 px-4 tracking-widest">
                        {{ $section['header'] }}
                    </li>

                    @foreach ($section['items'] as $menu)
                        <li>
                            <a href="{{ $menu['route'] }}"
                                class="group flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200
                               {{ isset($menu['active']) && $menu['active'] ? 'bg-primary/10 text-primary font-semibold active-menu' : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">

                                @if (isset($menu['icon']))
                                    @svg('lucide-' . $menu['icon'], 'w-5 h-5 transition-colors duration-200 ' . (isset($menu['active']) && $menu['active'] ? 'stroke-primary' : 'stroke-base-content/60 group-hover:stroke-base-content'))
                                @endif

                                <span>{{ $menu['title'] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </aside>
</div>

<style>
    /* Scrollbar Halus */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 20px;
    }

    /* Indikator Menu Aktif (Garis di Kiri) */
    .active-menu {
        border-left: 3px solid currentColor;
    }
</style>
