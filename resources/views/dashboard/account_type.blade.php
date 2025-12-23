<x-dashboard.main title="Daftar Tipe Akun">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ([
        [
            'label' => 'Total Kategori Akun',
            'value' => $tipe_akun->count(),
            'icon' => 'layers',
            'bg_color' => 'bg-indigo-50',
            'text_color' => 'text-indigo-600',
            'watermark' => 'tags',
        ],
        [
            'label' => 'Total Akun (COA)',
            'value' => \App\Models\Account::count(),
            'icon' => 'book-check',
            'bg_color' => 'bg-emerald-50',
            'text_color' => 'text-emerald-600',
            'watermark' => null,
        ],
        [
            'label' => 'Akun Aktif',
            'value' => \App\Models\Account::where('is_active', true)->count(),
            'icon' => 'activity',
            'bg_color' => 'bg-amber-50',
            'text_color' => 'text-amber-600',
            'watermark' => null,
        ],
    ] as $card)
            <div
                class="flex items-center p-4 bg-white rounded-xl border border-gray-200 shadow-sm relative overflow-hidden hover:shadow-md transition-shadow duration-300">

                @if ($card['watermark'])
                    <div class="absolute right-0 top-0 opacity-5 -mr-4 -mt-4 pointer-events-none">
                        <x-dynamic-component :component="'lucide-' . $card['watermark']" class="w-24 h-24 text-gray-800" />
                    </div>
                @endif

                <div class="p-3 {{ $card['bg_color'] }} {{ $card['text_color'] }} rounded-lg mr-4 z-10">
                    <x-dynamic-component :component="'lucide-' . $card['icon']" class="w-6 h-6" />
                </div>

                <div class="z-10">
                    <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                    <p class="text-xl font-bold text-gray-800">{{ $card['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200 mt-2">
        <div class="card-body p-0">

            <div class="p-5 border-b border-base-200 flex justify-between items-center bg-base-100 rounded-t-2xl">
                <div>
                    <h3 class="font-bold text-lg text-base-content">Daftar Tipe Akun</h3>
                    <p class="text-sm text-base-content/60">Kelola kategori akun untuk laporan keuangan.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full" id="dataTable">

                    <thead class="bg-base-200 text-base-content/70">
                        <tr>
                            <th class="w-16 text-center">No</th>
                            <th>Nama Tipe Akun</th>
                            <th class="w-40 text-center">Saldo Normal</th>
                            <th class="w-40 text-center">Jumlah Akun</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tipe_akun as $i => $item)
                            <tr class="hover:bg-base-200/50 transition-colors">
                                <td class="text-center font-mono text-xs">{{ $i + 1 }}</td>

                                <td class="font-medium text-base-content">
                                    {{ $item->name }}
                                </td>

                                <td class="text-center">
                                    @if ($item->normal_balance == 'debit')
                                        <div class="badge badge-info badge-outline gap-1 font-mono text-xs uppercase">
                                            <x-lucide-arrow-down-left class="w-3 h-3" /> Debit
                                        </div>
                                    @else
                                        <div
                                            class="badge badge-warning badge-outline gap-1 font-mono text-xs uppercase">
                                            <x-lucide-arrow-up-right class="w-3 h-3" /> Kredit
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center">

                                    <span class="badge badge-ghost text-xs">
                                        {{ $item->accounts_count ?? 0 }} Akun
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-base-content/50">
                                    <div class="flex flex-col items-center gap-2">
                                        <x-lucide-inbox class="w-10 h-10 opacity-20" />
                                        <span>Belum ada data tipe akun.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.main>
