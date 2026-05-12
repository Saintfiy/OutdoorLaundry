<?php
session_start();

$redirects = [
    'admin'    => 'dashboard_admin.php',
    'kurir'    => 'dashboard_kurir.php',
    'customer' => 'dashboard_customer.php',
];

if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? 'customer';

    header('Location: ' . ($redirects[$role] ?? $redirects['customer']));
    exit;
}

$errorMessage = isset($_GET['error'])
    ? 'Email atau password salah! Silakan periksa kembali.'
    : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login - Outdoor Laundry</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;700;800&display=swap"
        rel="stylesheet"
    >

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#FFF8F5',
                            100: '#FDF4F0',
                            500: '#F05023',
                            600: '#D9451D',
                            700: '#B83512',
                            900: '#7A2209',
                        }
                    }
                }
            }
        };
    </script>
</head>

<body class="bg-brand-50 min-h-screen flex items-center justify-center p-4 font-sans">

    <main class="w-full max-w-[1000px] overflow-hidden rounded-[2rem] bg-white shadow-xl flex flex-col md:flex-row">

        <!-- Left Section -->
        <section class="relative hidden w-1/2 overflow-hidden bg-brand-500 md:block">

            <img
                src="https://images.unsplash.com/photo-1551632811-561732d1e306?q=80&w=2070&auto=format&fit=crop"
                alt="Hiking Mountain"
                class="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-multiply grayscale"
            >

            <div class="absolute inset-0 bg-gradient-to-t from-brand-900/90 to-transparent"></div>

            <div class="relative z-10 flex h-full flex-col justify-end p-12 text-white">

                <a
                    href="index.php"
                    class="absolute left-8 top-8 flex items-center text-white/80 transition hover:text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20"
                         height="20"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="mr-2">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>

                    Kembali
                </a>

                <h2 class="font-display mb-4 text-4xl font-bold leading-tight">
                    Bersihkan Gearmu,<br>
                    Siap Bertualang Lagi.
                </h2>

                <p class="max-w-sm text-sm text-brand-100">
                    Layanan laundry profesional khusus peralatan outdoor.
                    Menggunakan treatment aman untuk material teknis.
                </p>

            </div>
        </section>

        <!-- Right Section -->
        <section class="flex w-full flex-col justify-center bg-white p-10 sm:p-14 md:w-1/2">

            <!-- Header -->
            <div class="mb-10">

                <div class="mb-6 flex items-center text-brand-500">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="28"
                         height="28"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2.5"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="mr-2">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                    </svg>

                    <span class="font-display text-xl font-bold tracking-tight text-slate-900">
                        OutdoorLaundry
                    </span>

                </div>

                <h1 class="font-display text-3xl font-bold text-slate-900">
                    Selamat Datang
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Silakan login untuk mengelola layanan laundry gear Anda.
                </p>

            </div>

            <!-- Alert -->
            <?php if ($errorMessage): ?>
                <div class="mb-6 flex items-start rounded-2xl border border-red-100 bg-red-50 p-4 text-sm font-medium text-red-600">

                    <svg class="mr-2 mt-0.5 h-5 w-5 shrink-0"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>

                    <span><?= htmlspecialchars($errorMessage) ?></span>

                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="auth_process.php" method="POST" class="space-y-5">

                <input type="hidden" name="action" value="login">

                <div>
                    <label class="mb-1.5 ml-1 block text-sm font-medium text-slate-700">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="pendaki@example.com"
                        class="w-full rounded-full border border-slate-100 bg-slate-50 px-5 py-3.5 text-sm outline-none transition-all focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-500"
                    >
                </div>

                <div>
                    <label class="mb-1.5 ml-1 block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-full border border-slate-100 bg-slate-50 px-5 py-3.5 text-sm outline-none transition-all focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-500"
                    >
                </div>

                <button
                    type="submit"
                    class="mt-2 w-full rounded-full bg-brand-500 py-3.5 font-bold text-white shadow-lg shadow-brand-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-brand-500/40"
                >
                    Masuk ke Dashboard
                </button>

            </form>

            <!-- Footer -->
            <p class="mt-8 text-center text-sm text-slate-500">

                Belum memiliki akun?

                <a
                    href="register.php"
                    class="font-bold text-brand-500 transition-colors hover:text-brand-600"
                >
                    Daftar sekarang
                </a>

            </p>

        </section>

    </main>

</body>
</html>
