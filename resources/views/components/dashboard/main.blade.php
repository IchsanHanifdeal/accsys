<x-main title="{{ $title }}" class="!p-0" full>

    <div class="drawer lg:drawer-open">
        <input id="aside-dashboard" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col min-h-screen bg-base-200 text-base-content">

            @include('components.dashboard.navbar')

            <div class="p-4 md:p-6 w-full max-w-7xl mx-auto flex-1 flex flex-col gap-6">

                <div class="space-y-3">
                    @if (session('success'))
                        <div role="alert" class="alert alert-success shadow-sm" x-data="{ show: true }" x-show="show"
                            x-transition>
                            <x-lucide-check-circle class="w-6 h-6 stroke-current" />
                            <div>
                                <h3 class="font-bold text-sm">Berhasil!</h3>
                                <div class="text-xs">{{ session('success') }}</div>
                            </div>
                            <button @click="show = false" class="btn btn-sm btn-circle btn-ghost">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div role="alert" class="alert alert-error shadow-sm" x-data="{ show: true }" x-show="show"
                            x-transition>
                            <x-lucide-alert-circle class="w-6 h-6 stroke-current" />
                            <div>
                                <h3 class="font-bold text-sm">Gagal!</h3>
                                <div class="text-xs">{{ session('error') }}</div>
                            </div>
                            <button @click="show = false" class="btn btn-sm btn-circle btn-ghost">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div role="alert" class="alert alert-warning shadow-sm" x-data="{ show: true }" x-show="show"
                            x-transition>
                            <x-lucide-alert-triangle class="w-6 h-6 stroke-current" />
                            <div>
                                <h3 class="font-bold text-sm">Perhatian!</h3>
                                <div class="text-xs">{{ session('warning') }}</div>
                            </div>
                            <button @click="show = false" class="btn btn-sm btn-circle btn-ghost">
                                <x-lucide-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    {{ $slot }}
                </div>

            </div>

            @include('components.dashboard.footer')

        </div>

        @include('components.dashboard.aside')
    </div>

    <script>
        // 1. Live Search Tabel (Global)
        // Fitur ini otomatis jalan jika ada input #searchInput dan tabel #dataTable di halaman manapun
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('dataTable');

            if (searchInput && table) {
                searchInput.addEventListener('keyup', function() {
                    const filter = searchInput.value.toLowerCase();
                    const rows = table.getElementsByTagName('tr');

                    // Mulai dari i=1 karena row 0 biasanya Header
                    for (let i = 1; i < rows.length; i++) {
                        let match = false;
                        // Ambil semua cell dalam row
                        const cells = rows[i].getElementsByTagName('td');

                        for (let j = 0; j < cells.length; j++) {
                            const cellText = cells[j].textContent || cells[j].innerText;
                            if (cellText.toLowerCase().indexOf(filter) > -1) {
                                match = true;
                                break;
                            }
                        }
                        rows[i].style.display = match ? '' : 'none';
                    }
                });
            }
        });

        // 2. Helper Close Modal (Untuk tombol submit di dalam modal)
        function closeAllModals(event) {
            // Mencegah double submit jika form butuh validasi JS dulu
            // Tapi membiarkan form submit normal jalan
            const form = event.target.closest("form");

            // Jika validasi HTML5 lolos (checkValidity), baru tutup modal visualnya
            if (form && form.checkValidity()) {
                // Cari modal terdekat atau semua modal terbuka
                const modal = event.target.closest('dialog') || document.querySelector('dialog[open]');
                if (modal && typeof modal.close === 'function') {
                    // Beri sedikit delay agar user melihat efek klik, atau biarkan loading state menghandle
                    // modal.close();
                    // Note: Sebaiknya modal ditutup otomatis oleh redirect halaman atau loading state,
                    // tapi jika AJAX, tutup manual di sini.
                }
            }
        }
    </script>

</x-main>
