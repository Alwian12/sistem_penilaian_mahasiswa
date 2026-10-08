<?php
$programStudi = $programStudi ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'STMIK Triguna Dharma'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">
    <header class="border-b border-blue-950/20 bg-[#064b7a] text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-10">
            <a href="<?php echo BASE_URL; ?>/" class="flex items-center gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-500 text-xl shadow-lg shadow-orange-950/30"><i class="bi bi-mortarboard-fill"></i></span><span><strong class="block text-sm tracking-wide">STMIK TRIGUNA DHARMA</strong><small class="mt-0.5 block text-[10px] uppercase tracking-[.18em] text-blue-100/70">Kampus Digital &middot; Medan</small></span></a>
            <nav class="flex items-center gap-2 sm:gap-3"><a href="#program-studi" class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-blue-100 hover:bg-white/10 sm:inline-block">Program Studi</a><a href="<?php echo BASE_URL; ?>/register/dosen" class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-blue-100 hover:bg-white/10 md:inline-block">Daftar Dosen</a><a href="<?php echo BASE_URL; ?>/login" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-3 py-2 text-sm font-extrabold text-white shadow-lg shadow-orange-950/20 transition hover:bg-orange-600"><i class="bi bi-box-arrow-in-right"></i><span>Login</span></a></nav>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden bg-[#064b7a] text-white">
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full border-[32px] border-orange-400/20"></div><div class="absolute -bottom-40 left-1/2 h-96 w-96 rounded-full border-[44px] border-white/5"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1.05fr_.95fr] lg:px-10 lg:py-24">
                <div>
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-orange-300/30 bg-orange-400/10 px-3 py-2 text-xs font-bold uppercase tracking-[.16em] text-orange-200"><i class="bi bi-stars"></i> Portal Akademik TGD</div>
                    <h1 class="max-w-2xl text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">Bangun masa depan digital bersama Triguna Dharma.</h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-blue-100/80 sm:text-lg">Kelola perjalanan akademik, mulai dari pendaftaran mahasiswa baru hingga rekap nilai, IPS, IPK, dan transkrip dalam satu portal.</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row"><a href="<?php echo BASE_URL; ?>/register" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-orange-500 px-5 py-3.5 text-sm font-extrabold text-white shadow-xl shadow-orange-950/20 transition hover:bg-orange-600"><i class="bi bi-person-plus-fill"></i> Daftar mahasiswa baru</a><a href="<?php echo BASE_URL; ?>/login" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/25 px-5 py-3.5 text-sm font-extrabold text-white transition hover:bg-white/10"><i class="bi bi-box-arrow-in-right"></i> Masuk portal</a></div>
                    <div class="mt-10 flex flex-wrap gap-x-6 gap-y-3 text-sm text-blue-100/70"><span><i class="bi bi-geo-alt-fill mr-1 text-orange-300"></i>Medan, Sumatera Utara</span><span><i class="bi bi-shield-check mr-1 text-orange-300"></i>Portal akademik terintegrasi</span></div>
                </div>
                <div class="relative mx-auto w-full max-w-md">
                    <div class="rounded-[2rem] border border-white/15 bg-white/10 p-5 shadow-2xl backdrop-blur-sm"><div class="flex items-center justify-between border-b border-white/10 pb-4"><div class="flex items-center gap-3"><div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-2xl"><i class="bi bi-mortarboard-fill"></i></div><div><p class="text-xs font-bold uppercase tracking-[.18em] text-orange-200">TGD</p><p class="font-extrabold">Academic Journey</p></div></div><i class="bi bi-three-dots text-xl text-blue-100/60"></i></div><div class="mt-5 grid grid-cols-2 gap-3"><div class="rounded-2xl bg-white p-4 text-slate-900"><i class="bi bi-bar-chart-fill text-xl text-[#0b5d8f]"></i><p class="mt-5 text-xs font-semibold text-slate-500">IPS &amp; IPK</p><p class="mt-1 text-lg font-extrabold">Terukur</p></div><div class="rounded-2xl bg-orange-500 p-4 text-white"><i class="bi bi-journal-check text-xl"></i><p class="mt-5 text-xs font-semibold text-orange-100">KRS</p><p class="mt-1 text-lg font-extrabold">Tersusun</p></div></div><div class="mt-3 rounded-2xl border border-white/10 bg-[#053d64] p-4"><div class="flex items-center justify-between"><span class="text-sm font-bold">Profil akademik</span><span class="rounded-full bg-green-400/15 px-2 py-1 text-[10px] font-bold text-green-200">TERHUBUNG</span></div><div class="mt-4 h-2 overflow-hidden rounded-full bg-white/10"><div class="h-full w-4/5 rounded-full bg-orange-400"></div></div><p class="mt-2 text-xs text-blue-100/60">Satu portal untuk seluruh aktivitas akademik.</p></div></div>
                </div>
            </div>
        </section>

        <section id="program-studi" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-10"><div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="text-xs font-bold uppercase tracking-[.18em] text-[#0b5d8f]">Pilihan pendidikan</p><h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Program studi</h2><p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">Pilih jalur akademik yang sesuai dengan minat dan rencana masa depanmu.</p></div><a href="<?php echo BASE_URL; ?>/register" class="inline-flex items-center gap-2 text-sm font-extrabold text-orange-600 hover:text-orange-700">Mulai pendaftaran <i class="bi bi-arrow-right"></i></a></div><div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"><?php foreach ($programStudi as $program): ?><div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl"><div class="flex items-center justify-between"><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-xl text-[#0b5d8f]"><i class="bi bi-mortarboard"></i></span><span class="rounded-full bg-orange-50 px-2.5 py-1 text-xs font-bold text-orange-700"><?php echo e($program['jenjang']); ?></span></div><h3 class="mt-5 text-lg font-extrabold text-slate-900"><?php echo e($program['nama_prodi']); ?></h3><p class="mt-2 text-sm text-slate-500">Program akademik STMIK Triguna Dharma.</p></div><?php endforeach; ?></div></section>
    </main>

    <footer class="bg-slate-950 text-slate-300"><div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-[1.2fr_.8fr_.8fr] lg:px-10"><div><div class="flex items-center gap-3 text-white"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500"><i class="bi bi-mortarboard-fill"></i></span><strong>STMIK TRIGUNA DHARMA</strong></div><p class="mt-4 max-w-sm text-sm leading-6 text-slate-400">Sistem informasi akademik untuk mendukung perjalanan pendidikan mahasiswa di Medan.</p></div><div><p class="font-bold text-white">Kontak Kampus</p><p class="mt-3 text-sm leading-6 text-slate-400">Jl. A.H. Nasution No. 73<br>Medan Johor, Sumatera Utara</p></div><div><p class="font-bold text-white">Akses Cepat</p><div class="mt-3 space-y-2 text-sm"><a href="<?php echo BASE_URL; ?>/register" class="block text-slate-400 hover:text-orange-300">Pendaftaran mahasiswa</a><a href="<?php echo BASE_URL; ?>/register/dosen" class="block text-slate-400 hover:text-orange-300">Pendaftaran dosen</a><a href="<?php echo BASE_URL; ?>/login" class="block text-slate-400 hover:text-orange-300">Login portal</a></div></div></div><div class="border-t border-white/10 px-4 py-4 text-center text-xs text-slate-500">&copy; <?php echo date('Y'); ?> STMIK Triguna Dharma. Academic Portal.</div></footer>
</body>
</html>
