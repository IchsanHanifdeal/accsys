<x-dashboard.main title="Daftar Akun (COA)">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <x-lucide-library class="w-8 h-8 text-primary" />
                Chart of Accounts (COA)
            </h1>
            <p class="text-sm text-base-content/60 mt-1">
                Kelola daftar akun buku besar untuk pencatatan transaksi.
            </p>
        </div>
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">

            <div
                class="p-4 border-b border-base-200 flex flex-col sm:flex-row justify-between gap-4 bg-base-100 rounded-t-2xl">
                <div class="flex items-center gap-2">

                    <select class="select select-sm select-bordered focus:select-primary">
                        <option disabled selected>Filter Tipe</option>
                        <option>Semua Tipe</option>
                        @foreach ($account_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full" id="dataTable">
                    <thead class="bg-base-200 text-base-content/70">
                        <tr>
                            <th class="w-16 text-center">No</th>
                            <th class="w-32">Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Tipe Akun</th>
                            <th class="w-24 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $i => $account)
                            <tr class="hover:bg-base-200/50 group transition-colors">
                                <td class="text-center font-mono text-xs text-base-content/50">{{ $i + 1 }}</td>

                                <td class="font-mono font-bold text-primary">
                                    {{ $account->code }}
                                </td>

                                <td>
                                    <div class="font-semibold text-base-content">{{ $account->name }}</div>
                                    @if ($account->description)
                                        <div class="text-xs text-base-content/50 truncate max-w-xs">
                                            {{ $account->description }}</div>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge badge-ghost badge-sm text-xs font-medium">
                                        {{ $account->accountType->name ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if ($account->is_active)
                                        <div class="badge badge-success badge-xs gap-1 text-white">Aktif</div>
                                    @else
                                        <div class="badge badge-error badge-xs gap-1 text-white">Nonaktif</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center text-base-content/40 gap-3">
                                        <x-lucide-book-dashed class="w-12 h-12" />
                                        <span class="text-sm">Belum ada data akun yang terdaftar.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($accounts, 'links'))
                <div class="p-4 border-t border-base-200 bg-base-100 rounded-b-2xl">
                    {{ $accounts->links() }}
                </div>
            @endif
        </div>
    </div>

</x-dashboard.main>
