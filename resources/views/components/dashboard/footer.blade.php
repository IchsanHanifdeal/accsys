<footer class="footer footer-center p-4 bg-base-100 text-base-content border-t border-base-200 z-10 mt-auto">
    <div class="w-full flex flex-col md:flex-row justify-between items-center px-4 gap-2">

        <aside class="text-sm">
            <p>
                &copy; {{ date('Y') }}
                <span class="font-bold text-neutral">AccSystem</span>.
                <span class="opacity-70">All Rights Reserved.</span>
            </p>
        </aside>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inisialisasi AOS (Jika library diload)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                once: true, // Ubah ke true agar animasi tidak berulang saat scroll balik (lebih pro)
                duration: 800,
                easing: "ease-out-cubic",
            });
        }

        // 2. Global Modal Helper (Untuk modal edit/delete dinamis)
        window.openModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal && typeof modal.showModal === 'function') {
                modal.showModal();
            }
        };

        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal && typeof modal.close === 'function') {
                modal.close();
            }
        };
    });
</script>
