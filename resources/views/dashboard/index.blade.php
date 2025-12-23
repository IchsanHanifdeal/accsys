<x-dashboard.main title="Dashboard Keuangan">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="space-y-6 animate-fade-in-up">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content">
                    Halo, {{ Auth::user()->name ?? 'Finance Staff' }} 👋
                </h1>
                <p class="text-base-content/60 text-sm mt-1">
                    Berikut adalah ringkasan keuangan periode <span
                        class="font-semibold text-primary">{{ now()->translatedFormat('F Y') }}</span>.
                </p>
            </div>

            @if(Route::has('jurnal_umum'))
            <div class="flex gap-2">
                <a href="{{ route('jurnal_umum') }}" class="btn btn-primary btn-sm text-primary-content shadow-sm">
                    <x-lucide-plus class="w-4 h-4" />
                    Buat Jurnal Baru
                </a>
                <button class="btn btn-neutral btn-sm btn-outline shadow-sm">
                    <x-lucide-download class="w-4 h-4" />
                    Export Laporan
                </button>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow">
                <div class="card-body p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-base-content/50 uppercase tracking-wider">Total Aset</p>
                            <h3 class="text-xl font-bold text-base-content mt-1">
                                Rp {{ number_format($totalAset, 0, ',', '.') }}
                            </h3>
                            <span class="text-xs text-emerald-600 flex items-center gap-1 mt-2 font-medium">
                                <x-lucide-trending-up class="w-3 h-3" /> Akumulasi s/d Saat Ini
                            </span>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <x-lucide-wallet class="w-5 h-5" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow">
                <div class="card-body p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-base-content/50 uppercase tracking-wider">Pendapatan
                                (Bulan Ini)</p>
                            <h3 class="text-xl font-bold text-base-content mt-1">
                                Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                            </h3>
                            <span class="text-xs text-emerald-600 flex items-center gap-1 mt-2 font-medium">
                                <x-lucide-calendar class="w-3 h-3" /> Periode {{ date('M Y') }}
                            </span>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <x-lucide-bar-chart-3 class="w-5 h-5" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow">
                <div class="card-body p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-base-content/50 uppercase tracking-wider">Total Beban
                            </p>
                            <h3 class="text-xl font-bold text-base-content mt-1">
                                Rp {{ number_format($bebanBulanIni, 0, ',', '.') }}
                            </h3>
                            <span class="text-xs text-rose-500 flex items-center gap-1 mt-2 font-medium">
                                <x-lucide-arrow-down-right class="w-3 h-3" /> Pengeluaran Operasional
                            </span>
                        </div>
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                            <x-lucide-pie-chart class="w-5 h-5" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-primary text-primary-content shadow-lg shadow-primary/20 border border-primary">
                <div class="card-body p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold opacity-80 uppercase tracking-wider">Laba Bersih</p>
                            <h3 class="text-2xl font-black mt-1">
                                Rp {{ number_format($labaBersih, 0, ',', '.') }}
                            </h3>
                            <p class="text-xs opacity-90 mt-2">
                                {{ $labaBersih >= 0 ? 'Profit (Untung)' : 'Loss (Rugi)' }} Bulan Ini
                            </p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl text-white">
                            <x-lucide-coins class="w-5 h-5" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-base-content">Arus Kas (6 Bulan Terakhir)</h3>
                        <div class="badge badge-outline text-xs">Jutaan Rupiah</div>
                    </div>

                    <div id="cashFlowChart" class="w-full h-80"></div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-5 border-b border-base-200 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-base-content flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span>
                            Jurnal Draft
                        </h3>
                        <span class="text-xs font-semibold text-base-content/50">
                            {{ $draftJournals->count() }} Pending
                        </span>
                    </div>

                    <div class="overflow-y-auto max-h-[300px]">
                        <ul class="divide-y divide-base-200">
                            @forelse($draftJournals as $draft)
                                <li class="p-4 hover:bg-base-200/50 transition cursor-pointer group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div
                                                class="font-semibold text-sm text-base-content group-hover:text-primary transition">
                                                {{ $draft->journal_number }}
                                            </div>
                                            <div class="text-xs text-base-content/60 mt-0.5 line-clamp-1">
                                                {{ $draft->description }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-sm text-base-content">

                                                Rp {{ number_format($draft->journalEntries->sum('debit'), 0, ',', '.') }}
                                            </div>
                                            <div class="text-[10px] text-base-content/50">
                                                {{ $draft->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex gap-2">
                                        @if(Route::has('jurnal_umum'))
                                            <a href="{{ route('jurnal_umum') }}" class="btn btn-xs btn-primary btn-outline">Lanjut Edit</a>
                                        @else
                                            <button class="btn btn-xs btn-primary btn-outline">Lanjut Edit</button>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="p-8 text-center text-base-content/50 flex flex-col items-center">
                                    <x-lucide-check-circle class="w-8 h-8 opacity-20 mb-2" />
                                    <span class="text-xs">Tidak ada jurnal draft. Semua pekerjaan selesai!</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-0">
                <div
                    class="p-5 border-b border-base-200 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <h3 class="font-bold text-lg text-base-content">Jurnal Terakhir Diposting</h3>
                    @if(Route::has('riwayat_jurnal'))
                        <a href="{{ route('riwayat_jurnal') }}" class="btn btn-sm btn-ghost text-primary">
                            Lihat Selengkapnya <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead class="bg-base-200/50 text-base-content/70">
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Jurnal</th>
                                <th>Keterangan</th>
                                <th>Nominal Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentJournals as $jurnal)
                                <tr>
                                    <td class="text-xs font-mono">
                                        {{ \Carbon\Carbon::parse($jurnal->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td class="font-medium text-xs font-mono text-primary">
                                        {{ $jurnal->journal_number }}
                                    </td>
                                    <td>
                                        <div class="font-medium text-sm line-clamp-1">{{ $jurnal->description }}</div>
                                        <div class="text-xs text-base-content/50">{{ $jurnal->reference_no ?? '-' }}</div>
                                    </td>
                                    <td class="font-bold text-base-content text-sm">
                                        Rp {{ number_format($jurnal->journalEntries->sum('debit'), 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="badge badge-success badge-sm gap-1 text-white">Posted</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-base-content/50">
                                        Belum ada transaksi yang diposting.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const labels = @json($chartLabels);
            const dataPemasukan = @json($chartPemasukan);
            const dataPengeluaran = @json($chartPengeluaran);

            var options = {
                series: [{
                    name: 'Pemasukan',
                    data: dataPemasukan
                }, {
                    name: 'Pengeluaran',
                    data: dataPengeluaran
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'inherit',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 6
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: labels, // Label Bulan dari Controller
                },
                yaxis: {
                    title: {
                        text: 'Jutaan (Rp)'
                    }
                },
                fill: {
                    opacity: 1
                },
                // Warna Bumblebee Theme (Kuning/Emas & Hitam/Abu)
                colors: ['#E0A82E', '#1f2937'],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp " + val + " Juta"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#cashFlowChart"), options);
            chart.render();
        });
    </script>
</x-dashboard.main>
