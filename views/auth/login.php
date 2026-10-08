<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Login'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-800">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_15%,rgba(20,184,166,.22),transparent_35%),radial-gradient(circle_at_85%_85%,rgba(14,165,233,.18),transparent_35%)]"></div>
        <div class="relative grid w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-2xl lg:grid-cols-[.9fr_1.1fr]">
            <div class="hidden flex-col justify-between bg-[#064b7a] p-10 text-white lg:flex">
                <div>
                    <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500 text-2xl shadow-lg shadow-orange-950/30"><i class="bi bi-mortarboard-fill"></i></div>
                    <p class="text-xs font-bold uppercase tracking-[.2em] text-orange-300">Academic Portal</p>
                    <h2 class="mt-4 text-4xl font-extrabold leading-tight">Kelola perjalanan akademik dengan lebih terarah.</h2>
                    <p class="mt-5 max-w-sm text-sm leading-7 text-slate-300">Satu ruang untuk rekap nilai, IPS, IPK, KRS, dan transkrip mahasiswa STMIK Triguna Dharma.</p>
                </div>
                <div class="flex items-center gap-3 text-sm text-blue-100/70"><i class="bi bi-shield-check text-xl text-orange-300"></i> Data akademik tersusun dalam satu sistem</div>
            </div>
            <div class="p-7 sm:p-10">
                <div class="mb-8 lg:hidden">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-xl text-orange-700"><i class="bi bi-mortarboard-fill"></i></div>
                    <p class="text-xs font-bold uppercase tracking-[.18em] text-[#064b7a]">STMIK Triguna Dharma</p>
                </div>
                <div class="mb-8">
                    <p class="text-sm font-bold text-[#064b7a]">Selamat datang kembali</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-ink">Masuk ke portal akademik</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan akun yang sudah terdaftar untuk melanjutkan.</p>
                </div>

                <?php $success = flash('success'); if ($success): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><i class="bi bi-check-circle-fill mt-0.5"></i><span><?php echo e($success); ?></span></div>
                <?php endif; ?>
                <?php $error = flash('error'); if ($error): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i class="bi bi-exclamation-circle-fill mt-0.5"></i><span><?php echo e($error); ?></span></div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>/login" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label for="identifier" class="mb-2 block text-sm font-bold text-slate-700">Username atau email</label>
                        <div class="relative"><i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input id="identifier" name="identifier" type="text" required autocomplete="username" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between"><label for="password" class="block text-sm font-bold text-slate-700">Password</label></div>
                        <div class="relative"><i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i><input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-12 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"><button type="button" id="togglePassword" class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-xl text-slate-400 hover:bg-slate-200 hover:text-slate-700" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button></div>
                    </div>
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600"><i class="bi bi-box-arrow-in-right"></i> Masuk ke sistem</button>
                </form>

                <div class="my-7 flex items-center gap-3 text-xs text-slate-400"><span class="h-px flex-1 bg-slate-200"></span><span>Mahasiswa baru?</span><span class="h-px flex-1 bg-slate-200"></span></div>
                <a href="<?php echo BASE_URL; ?>/register" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-[#0b5d8f] px-4 py-3 text-sm font-extrabold text-[#064b7a] transition hover:bg-blue-50"><i class="bi bi-person-plus"></i> Daftar akun mahasiswa</a>
                <a href="<?php echo BASE_URL; ?>/register/dosen" class="mt-3 flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-600 transition hover:border-orange-300 hover:bg-orange-50 hover:text-orange-700"><i class="bi bi-person-workspace"></i> Daftar sebagai dosen</a>

                <p class="mt-8 text-center text-xs text-slate-400">Sistem Informasi Rekap Nilai &middot; STMIK Triguna Dharma</p>
            </div>
        </div>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        togglePassword?.addEventListener('click', () => {
            const visible = passwordInput.type === 'text';
            passwordInput.type = visible ? 'password' : 'text';
            togglePassword.innerHTML = `<i class="bi bi-eye${visible ? '' : '-slash'}"></i>`;
            togglePassword.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
        });
    </script>
</body>
</html>
