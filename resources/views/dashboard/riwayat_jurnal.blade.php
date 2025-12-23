<x-dashboard.main title="Riwayat Jurnal">

    <div class="flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                    <x-lucide-history class="w-8 h-8 text-primary" />
                    Riwayat Transaksi
                </h1>
                <p class="text-sm text-base-content/60 mt-1">
                    Daftar seluruh jurnal yang telah tercatat dalam sistem.
                </p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-outline btn-neutral gap-2">
                    <x-lucide-printer class="w-4 h-4" /> Cetak
                </button>
                <button class="btn btn-sm btn-primary text-white gap-2">
                    <x-lucide-download class="w-4 h-4" /> Export Excel
                </button>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-4 sm:p-5">
                <form method="GET" action="{{ route('riwayat_jurnal') }}">
                    <div class="flex flex-col md:flex-row gap-4 items-end">

                        <div class="form-control w-full md:w-auto">
                            <label class="label text-xs font-bold text-base-content/60">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}"
                                class="input input-sm input-bordered focus:input-primary">
                        </div>

                        <div class="form-control w-full md:w-auto">
                            <label class="label text-xs font-bold text-base-content/60">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-t')) }}"
                                class="input input-sm input-bordered focus:input-primary">
                        </div>

                        <div class="form-control w-full md:flex-1">
                            <label class="label text-xs font-bold text-base-content/60">Pencarian</label>
                            <div class="join w-full">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="input input-sm input-bordered join-item w-full focus:input-primary"
                                    placeholder="Cari No. Jurnal, Referensi, atau Keterangan...">
                                <button type="submit" class="btn btn-sm btn-neutral join-item">
                                    <x-lucide-search class="w-4 h-4" /> Cari
                                </button>
                            </div>
                        </div>

                        @if (request()->has('search') || request()->has('start_date'))
                            <a href="{{ route('riwayat_jurnal') }}" class="btn btn-sm btn-ghost text-error"
                                title="Reset Filter">
                                <x-lucide-x-circle class="w-5 h-5" />
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200 text-base-content/70">
                            <tr>
                                <th class="pl-6 w-32">Tanggal</th>
                                <th class="w-40">No. Jurnal</th>
                                <th>Keterangan / Deskripsi</th>
                                <th class="w-32">Ref.</th>
                                <th class="text-right w-40">Total (Rp)</th>
                                <th class="text-center w-24">Status</th>
                                <th class="text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($journals as $journal)
                                <tr class="hover:bg-base-100 text-sm group transition-colors">

                                    <td class="pl-6 font-mono text-xs">
                                        {{ \Carbon\Carbon::parse($journal->transaction_date)->format('d M Y') }}
                                    </td>

                                    <td class="font-bold text-primary text-xs font-mono">
                                        {{ $journal->journal_number }}
                                    </td>

                                    <td>
                                        <div class="font-medium text-base-content line-clamp-1">
                                            {{ $journal->description }}
                                        </div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $journal->journalEntries->count() }} Baris Akun
                                        </div>
                                    </td>

                                    <td class="text-xs text-base-content/60">
                                        {{ $journal->reference_no ?? '-' }}
                                    </td>

                                    <td class="text-right font-mono font-semibold">
                                        {{ number_format($journal->journalEntries->sum('debit'), 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if ($journal->status == 'posted')
                                            <div class="badge badge-success badge-xs text-white gap-1">Posted</div>
                                        @else
                                            <div class="badge badge-warning badge-xs text-white gap-1">Draft</div>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <button
                                            onclick="document.getElementById('detail_modal_{{ $journal->id }}').showModal()"
                                            class="btn btn-square btn-ghost btn-xs text-info hover:bg-info/10">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </button>

                                        <dialog id="detail_modal_{{ $journal->id }}"
                                            class="modal modal-bottom sm:modal-middle text-left">
                                            <div class="modal-box w-11/12 max-w-3xl bg-base-100 p-0 overflow-hidden">

                                                <div
                                                    class="bg-base-200 p-4 border-b border-base-300 flex justify-between items-center">
                                                    <div>
                                                        <h3 class="font-bold text-lg">Detail Transaksi</h3>
                                                        <p class="text-xs opacity-70">{{ $journal->journal_number }} •
                                                            {{ \Carbon\Carbon::parse($journal->transaction_date)->translatedFormat('d F Y') }}
                                                        </p>
                                                    </div>
                                                    <form method="dialog">
                                                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                                                    </form>
                                                </div>

                                                <div class="p-6">
                                                    <div class="mb-4 p-3 bg-base-100 border border-base-200 rounded-lg">
                                                        <span
                                                            class="text-xs font-bold text-base-content/50 uppercase">Deskripsi</span>
                                                        <p class="text-sm mt-1">{{ $journal->description }}</p>
                                                    </div>

                                                    <div class="overflow-x-auto border border-base-200 rounded-lg">
                                                        <table class="table table-sm w-full">
                                                            <thead class="bg-base-200">
                                                                <tr>
                                                                    <th>Kode</th>
                                                                    <th>Nama Akun</th>
                                                                    <th class="text-right">Debit</th>
                                                                    <th class="text-right">Kredit</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($journal->journalEntries as $entry)
                                                                    <tr>
                                                                        <td class="font-mono text-xs">
                                                                            {{ $entry->account->code ?? '-' }}</td>
                                                                        <td>{{ $entry->account->name ?? '-' }}</td>
                                                                        <td
                                                                            class="text-right font-mono text-xs {{ $entry->debit > 0 ? 'text-base-content font-bold' : 'text-base-content/30' }}">
                                                                            {{ $entry->debit > 0 ? number_format($entry->debit, 0, ',', '.') : '-' }}
                                                                        </td>
                                                                        <td
                                                                            class="text-right font-mono text-xs {{ $entry->credit > 0 ? 'text-base-content font-bold' : 'text-base-content/30' }}">
                                                                            {{ $entry->credit > 0 ? number_format($entry->credit, 0, ',', '.') : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot class="bg-base-100 font-bold border-t">
                                                                <tr>
                                                                    <td colspan="2" class="text-right">Total</td>
                                                                    <td class="text-right">
                                                                        {{ number_format($journal->journalEntries->sum('debit'), 0, ',', '.') }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ number_format($journal->journalEntries->sum('credit'), 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="bg-base-200 p-4 flex justify-end">
                                                    <form method="dialog">
                                                        <button class="btn btn-sm btn-primary text-white">Tutup</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                        </dialog>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12">
                                        <div
                                            class="flex flex-col items-center justify-center text-base-content/40 gap-3">
                                            <x-lucide-file-x class="w-12 h-12" />
                                            <span class="text-sm">Tidak ada riwayat transaksi ditemukan pada periode
                                                ini.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($journals->hasPages())
                    <div class="p-4 border-t border-base-200 bg-base-100 rounded-b-2xl">
                        {{ $journals->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-dashboard.main>
