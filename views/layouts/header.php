<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        ink: '#122235',
                        brand: '#0b5d8f',
                        'brand-dark': '#064b7a',
                        orange: '#f97316',
                        mist: '#f4f7fb'
                    },
                    boxShadow: {
                        soft: '0 12px 30px rgba(18, 34, 53, 0.07)'
                    }
                }
            }
        };
    </script>
    <style>
        :root { color-scheme: light; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link { transition: background-color .2s ease, color .2s ease, transform .2s ease; }
        .nav-link:hover { transform: translateX(2px); }
        .mobile-backdrop { transition: opacity .2s ease; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.is-open { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-mist text-ink antialiased">
    <?php
        $role = $_SESSION['user']['role'] ?? '';
        $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $menus = $role === 'admin'
            ? [
                ['Dashboard', '/admin/dashboard', 'bi-grid-1x2-fill'],
                ['Mahasiswa', '/admin/mahasiswa', 'bi-people-fill'],
                ['Dosen', '/admin/dosen', 'bi-person-workspace'],
                ['Program Studi', '/admin/program-studi', 'bi-diagram-3-fill'],
                ['Mata Kuliah', '/admin/mata-kuliah', 'bi-journal-bookmark-fill'],
                ['Tahun Akademik', '/admin/tahun-akademik', 'bi-calendar3'],
                ['KRS', '/admin/krs', 'bi-clipboard2-check-fill'],
                ['Nilai', '/admin/nilai', 'bi-pencil-square'],
                ['IPS', '/admin/ips', 'bi-bar-chart-fill'],
                ['IPK', '/admin/ipk', 'bi-speedometer2'],
                ['Transkrip', '/admin/transkrip', 'bi-file-earmark-text-fill'],
            ]
            : ($role === 'dosen'
                ? [['Dashboard', '/dosen/dashboard', 'bi-grid-1x2-fill'], ['Input Nilai', '/admin/nilai', 'bi-pencil-square'], ['Transkrip', '/admin/transkrip', 'bi-file-earmark-text-fill']]
                : [['Dashboard', '/mahasiswa/dashboard', 'bi-grid-1x2-fill'], ['Pengisian KRS', '/mahasiswa/krs', 'bi-journal-plus'], ['IPS', '/admin/ips', 'bi-bar-chart-fill'], ['IPK', '/admin/ipk', 'bi-speedometer2'], ['Transkrip', '/admin/transkrip', 'bi-file-earmark-text-fill']]);
    ?>
    <div id="mobileBackdrop" class="mobile-backdrop fixed inset-0 z-30 hidden bg-ink/40 opacity-0 lg:hidden"></div>
    <aside id="appSidebar" class="sidebar fixed inset-y-0 left-0 z-40 flex w-72 flex-col bg-[#064b7a] px-4 py-5 text-white shadow-2xl lg:translate-x-0">
        <div class="flex items-center gap-3 px-3 pb-8">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-500 text-xl shadow-lg shadow-orange-950/30"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <p class="text-[13px] font-extrabold tracking-wide">STMIK TRIGUNA DHARMA</p>
                <p class="mt-0.5 text-[10px] font-medium uppercase tracking-[.16em] text-slate-400">Kampus Digital &middot; Medan</p>
            </div>
        </div>
        <div class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[.2em] text-slate-500">Menu Utama</div>
        <nav class="flex-1 space-y-1 overflow-y-auto">
            <?php foreach ($menus as $menu):
                $href = BASE_URL . $menu[1];
                $isActive = $currentPath === $menu[1] || ($menu[1] !== '/admin/dashboard' && str_starts_with($currentPath, $menu[1]));
            ?>
                <a href="<?php echo $href; ?>" class="nav-link flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold <?php echo $isActive ? 'bg-orange-500 text-white shadow-lg shadow-orange-950/20' : 'text-blue-100/70 hover:bg-white/10 hover:text-white'; ?>">
                    <i class="bi <?php echo e($menu[2]); ?> text-base"></i><span><?php echo e($menu[0]); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="mt-5 rounded-2xl border border-white/10 bg-white/5 p-3">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-400 font-bold text-ink"><i class="bi bi-person-fill"></i></div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold"><?php echo e($_SESSION['user']['nama'] ?? 'User'); ?></p>
                    <p class="text-xs capitalize text-slate-400"><?php echo e($role ?: 'User'); ?></p>
                </div>
            </div>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-72">
        <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-10">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" type="button" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Buka menu"><i class="bi bi-list text-xl"></i></button>
                    <div>
                        <div class="flex items-center gap-2"><p class="text-xs font-bold uppercase tracking-[.18em] text-[#064b7a]">Sistem Akademik</p><span class="rounded-full bg-orange-50 px-2 py-1 text-[10px] font-extrabold uppercase tracking-wider text-orange-700">TGD 2026</span></div>
                        <p class="mt-1 hidden text-sm text-slate-500 sm:block">Rekap nilai, IPS, IPK, dan transkrip mahasiswa</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo BASE_URL; ?>/logout">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-sm font-bold text-red-600 transition hover:bg-red-100"><i class="bi bi-box-arrow-right"></i><span class="hidden sm:inline">Keluar</span></button>
                </form>
            </div>
        </header>
        <main>
