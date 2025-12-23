<x-dashboard.main title="Jurnal Umum">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex flex-col gap-6">

        <div class="collapse collapse-arrow bg-indigo-50 border border-indigo-100 shadow-sm">
            <input type="checkbox" />
            <div class="collapse-title flex items-center gap-3 font-bold text-indigo-900">
                <div class="p-2 bg-indigo-600 text-white rounded-lg shadow-sm">
                    <x-lucide-calculator class="w-5 h-5" />
                </div>
                <div>
                    <span class="text-lg">Kalkulator Simulasi Pinjaman</span>
                    <p class="text-xs font-normal text-indigo-700 mt-0.5">Bantu hitung pencairan & angsuran otomatis (Opsional)</p>
                </div>
            </div>
            <div class="collapse-content">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end mt-2">
                    <div class="form-control w-full">
                        <label class="label text-xs font-bold text-indigo-800 uppercase">Plafon (Rp)</label>
                        <input type="number" id="calc_plafon" class="input input-sm input-bordered border-indigo-300 w-full" placeholder="Contoh: 30000000">
                    </div>
                    <div class="form-control w-full">
                        <label class="label text-xs font-bold text-indigo-800 uppercase">Tenor (Bulan)</label>
                        <input type="number" id="calc_tenor" class="input input-sm input-bordered border-indigo-300 w-full" placeholder="Contoh: 5">
                    </div>
                    <div class="form-control w-full">
                        <label class="label text-xs font-bold text-indigo-800 uppercase">Bunga/Bulan (%)</label>
                        <input type="number" id="calc_bunga" class="input input-sm input-bordered border-indigo-300 w-full" placeholder="Contoh: 2.5">
                    </div>
                    <div class="grid grid-cols-2 gap-2 w-full">
                        <button type="button" onclick="generateTemplate('pencairan')" class="btn btn-sm bg-indigo-600 text-white hover:bg-indigo-700 border-0">
                            <x-lucide-download class="w-4 h-4" /> Pencairan
                        </button>
                        <button type="button" onclick="generateTemplate('angsuran')" class="btn btn-sm btn-outline border-indigo-600 text-indigo-700 hover:bg-indigo-50">
                            <x-lucide-repeat class="w-4 h-4" /> Angsuran
                        </button>
                    </div>
                </div>
                <div class="text-xs text-indigo-500 mt-3 italic">
                    *Catatan: Fitur ini hanya template. Anda tetap dapat mengubah akun dan nominal setelah di-generate.
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <form action="{{ route('jurnal_umum.store') }}" method="POST" id="journalForm">
                @csrf

                <div class="p-4 sm:p-6 border-b border-base-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                        <div class="form-control">
                            <label class="label font-medium text-sm">Nomor Jurnal</label>
                            <input type="text" name="journal_number" value="{{ $next_number ?? 'JRN-NEW' }}" class="input input-bordered w-full font-mono bg-base-200 text-sm" readonly />
                        </div>
                        <div class="form-control">
                            <label class="label font-medium text-sm">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" id="trx_date" value="{{ date('Y-m-d') }}" class="input input-bordered w-full text-sm" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-medium text-sm">No. Referensi <span class="text-xs text-gray-400">(Opsional)</span></label>
                            <input type="text" name="reference_no" placeholder="Contoh: INV-001" class="input input-bordered w-full text-sm" />
                        </div>
                        <div class="form-control md:col-span-3">
                            <label class="label font-medium text-sm">Keterangan / Deskripsi</label>
                            <textarea name="description" id="trx_desc" class="textarea textarea-bordered h-20 text-sm w-full" placeholder="Jelaskan detail transaksi ini..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <h3 class="font-bold text-lg text-base-content">Rincian Akun</h3>
                        <div class="flex gap-2">
                            <button type="button" onclick="resetForm()" class="btn btn-sm btn-ghost text-error hover:bg-error/10">
                                <x-lucide-rotate-ccw class="w-4 h-4" /> Reset
                            </button>
                            <button type="button" onclick="addRow()" class="btn btn-sm btn-outline btn-primary">
                                <x-lucide-plus class="w-4 h-4" /> Tambah Baris
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-base-200">
                        <table class="table table-sm w-full min-w-[600px]" id="entriesTable">
                            <thead class="bg-base-200 text-base-content/70">
                                <tr>
                                    <th class="w-[40%] pl-4">Akun</th>
                                    <th class="w-[25%] text-right">Debit (Rp)</th>
                                    <th class="w-[25%] text-right">Kredit (Rp)</th>
                                    <th class="w-[10%] text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                            <tfoot class="bg-base-100 font-bold border-t-2 border-base-200 text-sm">
                                <tr>
                                    <td class="text-right pr-4 uppercase text-xs tracking-wider">Total</td>
                                    <td class="text-right pr-4 font-mono text-base-content" id="totalDebit">0</td>
                                    <td class="text-right pr-4 font-mono text-base-content" id="totalCredit">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <div id="balanceAlert" class="alert alert-sm bg-base-200 text-base-content/60 rounded-lg flex items-center gap-2 border-0 transition-all duration-300">
                            <x-lucide-info class="w-4 h-4" />
                            <span class="text-xs">Silakan input nominal debit dan kredit.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-base-200 pt-5">
                        <button type="submit" id="btnSave" class="btn btn-primary text-primary-content w-full sm:w-auto px-8 shadow-md gap-2" disabled>
                            <x-lucide-save class="w-4 h-4" /> Simpan Jurnal
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script>
        const accounts = @json($accounts);
        let rowCount = 0;

        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                });
            @endif

            addRow();
            addRow();
        });

        function addRow(data = {}) {
            const tbody = document.getElementById('tableBody');
            const rowId = rowCount++;

            let selectedId = '';
            if (data.code) {
                const found = accounts.find(a => a.code == data.code);
                if (found) selectedId = found.id;
            }

            let options = '<option value="" disabled selected>-- Pilih Akun --</option>';
            accounts.forEach(acc => {
                const isSel = (acc.id == selectedId) ? 'selected' : '';
                options += `<option value="${acc.id}" ${isSel}>[${acc.code}] ${acc.name}</option>`;
            });

            const debitVal = data.debit || 0;
            const creditVal = data.credit || 0;

            const tr = document.createElement('tr');
            tr.id = `row-${rowId}`;
            tr.innerHTML = `
                <td class="pl-4">
                    <select name="details[${rowId}][account_id]" class="select select-bordered select-sm w-full font-mono text-xs focus:select-primary" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="details[${rowId}][debit]" value="${debitVal}"
                        class="input input-bordered input-sm w-full text-right debit-input font-mono text-xs focus:input-primary"
                        oninput="calcTotals()" onfocus="this.select()" placeholder="0" step="0.01">
                </td>
                <td>
                    <input type="number" name="details[${rowId}][credit]" value="${creditVal}"
                        class="input input-bordered input-sm w-full text-right credit-input font-mono text-xs focus:input-primary"
                        oninput="calcTotals()" onfocus="this.select()" placeholder="0" step="0.01">
                </td>
                <td class="text-center">
                    <button type="button" onclick="removeRow(${rowId})" class="btn btn-ghost btn-xs text-error hover:bg-error/10" title="Hapus Baris">
                        <x-lucide-trash class="w-4 h-4" />
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            calcTotals();
        }

        function removeRow(id) {
            const row = document.getElementById(`row-${id}`);
            if (document.querySelectorAll('#tableBody tr').length > 1) {
                row.remove();
                calcTotals();
            } else {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000
                });
                Toast.fire({
                    icon: "warning",
                    title: "Minimal harus ada 1 baris jurnal."
                });

                row.querySelectorAll('input').forEach(i => i.value = 0);
                row.querySelector('select').selectedIndex = 0;
                calcTotals();
            }
        }

        function resetForm() {
            Swal.fire({
                title: 'Reset Formulir?',
                text: "Semua data yang belum disimpan akan hilang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('tableBody').innerHTML = '';
                    document.getElementById('trx_desc').value = '';
                    document.getElementById('calc_plafon').value = '';
                    document.getElementById('calc_tenor').value = '';
                    document.getElementById('calc_bunga').value = '';
                    addRow();
                    addRow();
                    calcTotals();
                    Swal.fire({
                        title: "Direset!",
                        text: "Formulir kembali kosong.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function calcTotals() {
            let totalDebit = 0;
            let totalCredit = 0;

            document.querySelectorAll('.debit-input').forEach(el => totalDebit += parseFloat(el.value) || 0);
            document.querySelectorAll('.credit-input').forEach(el => totalCredit += parseFloat(el.value) || 0);

            const fmt = new Intl.NumberFormat('id-ID');
            document.getElementById('totalDebit').innerText = fmt.format(totalDebit);
            document.getElementById('totalCredit').innerText = fmt.format(totalCredit);

            const alertBox = document.getElementById('balanceAlert');
            const btnSave = document.getElementById('btnSave');

            if (totalDebit > 0 && Math.abs(totalDebit - totalCredit) < 1) {
                alertBox.className = "alert alert-sm alert-success text-white rounded-lg flex items-center gap-2 mt-2 py-2 shadow-sm";
                alertBox.innerHTML = `<x-lucide-check-circle class="w-4 h-4" /> <span class="text-xs font-bold">Balance! Jurnal siap disimpan.</span>`;
                btnSave.disabled = false;
            } else if (totalDebit === 0 && totalCredit === 0) {
                alertBox.className = "alert alert-sm bg-base-200 text-base-content/60 rounded-lg flex items-center gap-2 mt-2 py-2 border-0";
                alertBox.innerHTML = `<x-lucide-info class="w-4 h-4" /> <span class="text-xs">Silakan input nominal.</span>`;
                btnSave.disabled = true;
            } else {
                const diff = Math.abs(totalDebit - totalCredit);
                alertBox.className = "alert alert-sm alert-error text-white rounded-lg flex items-center gap-2 mt-2 py-2 shadow-sm";
                alertBox.innerHTML = `<x-lucide-alert-triangle class="w-4 h-4" /> <span class="text-xs font-bold">Tidak Seimbang (Selisih: Rp ${fmt.format(diff)})</span>`;
                btnSave.disabled = true;
            }
        }

        function generateTemplate(type) {
            const plafon = parseFloat(document.getElementById('calc_plafon').value) || 0;
            const tenor = parseFloat(document.getElementById('calc_tenor').value) || 0;
            const bungaPercent = parseFloat(document.getElementById('calc_bunga').value) || 0;

            if (plafon <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon isi "Plafon Pinjaman" terlebih dahulu untuk melakukan perhitungan.'
                });
                return;
            }

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: "success",
                title: "Template berhasil diterapkan"
            });

            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            if (type === 'pencairan') {
                document.getElementById('trx_desc').value = `Pencairan Pinjaman Anggota (Plafon: Rp ${new Intl.NumberFormat('id-ID').format(plafon)})`;

                addRow({ code: '102', debit: plafon, credit: 0 });
                addRow({ code: '101', debit: 0, credit: plafon });

            } else if (type === 'angsuran') {
                if (tenor <= 0) {
                    Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Mohon isi "Tenor" agar perhitungan akurat.' });
                    return;
                }

                const pokok = Math.round(plafon / tenor);
                const bunga = Math.round(plafon * (bungaPercent / 100));
                const total = pokok + bunga;

                document.getElementById('trx_desc').value = `Setoran Angsuran (Pokok + Bunga)`;

                addRow({ code: '101', debit: total, credit: 0 });
                addRow({ code: '102', debit: 0, credit: pokok });
                addRow({ code: '401', debit: 0, credit: bunga });
            }
        }
    </script>
</x-dashboard.main>
