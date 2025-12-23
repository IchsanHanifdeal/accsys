<!DOCTYPE html>
<html lang="id" data-theme="bumblebee">

<head>
    @include('components.head')
    <style>
        /* Toast Transitions */
        .toast {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .toast-show {
            opacity: 1;
        }

        /* Animations */
        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        @keyframes gradient-move {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .fade-scale-in {
            animation: fadeScaleIn 0.8s ease forwards;
        }

        /* Override Word colors if pasted from MS Word */
        [style*="windowtext"] {
            color: inherit !important;
        }
    </style>
    {!! ToastMagic::styles() !!}
</head>

<body class="flex flex-col mx-auto min-h-screen font-sans text-base-content bg-base-200">

    <div id="splash-screen"
        class="fixed inset-0 z-[9999] flex flex-col items-center justify-center min-h-screen
        bg-gradient-to-br from-yellow-400 via-amber-200 to-base-100
        bg-[length:200%_200%] animate-[gradient-move_6s_ease_infinite]
        transition-opacity duration-500 opacity-100">

        <div class="relative w-24 h-24 mb-6">
            <div class="absolute inset-0 rounded-full border-[6px] border-neutral border-t-transparent animate-spin">
            </div>
            <div
                class="absolute inset-3 rounded-full border-[6px] border-white/60 border-b-transparent animate-spin-slow">
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-2xl font-bold text-neutral">Rp</span>
            </div>
        </div>

        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-neutral mb-1">
                Accounting System
            </h1>
            <p class="text-neutral-content font-medium text-sm tracking-wide uppercase opacity-80">
                Memuat Data Keuangan...
            </p>
        </div>
    </div>

    <main class="{{ $class ?? 'p-4' }} w-full mx-auto" role="main">
        {{ $slot }}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var splashScreen = document.getElementById('splash-screen');
                // Mencegah scroll saat splash screen tampil
                document.body.classList.add('overflow-hidden');

                // Tampilkan splash screen (ensure class is there)
                splashScreen.classList.remove('opacity-0', 'pointer-events-none');

                window.addEventListener('load', function() {
                    // Hilangkan splash screen setelah page fully loaded
                    setTimeout(() => {
                        splashScreen.classList.add('opacity-0', 'pointer-events-none');
                        document.body.classList.remove('overflow-hidden');
                    }, 500); // Sedikit delay agar transisi halus
                });
            });

            // Tampilkan lagi saat user navigasi keluar (pindah halaman)
            window.addEventListener('beforeunload', function() {
                var splashScreen = document.getElementById('splash-screen');
                splashScreen.classList.remove('opacity-0', 'pointer-events-none');
                document.body.classList.add('overflow-hidden');
            });

            function closeAllModals(event) {
                const form = event.target.closest("form");

                if (form) {
                    form.submit();
                    // Menutup dialog DaisyUI
                    const modals = document.querySelectorAll("dialog.modal");
                    modals.forEach((modal) => {
                        if (modal.hasAttribute("open") || modal.classList.contains('modal-open')) {
                            modal.close();
                            modal.classList.remove('modal-open');
                        }
                    });
                }
            }
        </script>

    </main>

    {!! ToastMagic::scripts() !!}
</body>

</html>
