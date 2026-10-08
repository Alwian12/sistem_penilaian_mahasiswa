<?php
$form = $form ?? [];
$error = flash('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Pendaftaran Mahasiswa'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-800">
    <div class="relative min-h-screen overflow-hidden px-4 py-8 sm:py-12">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_10%_5%,rgba(249,115,22,.2),transparent_32%),radial-gradient(circle_at_90%_90%,rgba(14,116,144,.22),transparent_35%)]"></div>
        <div class="relative mx-auto max-w-6xl">
            <div class="mb-6 flex items-center justify-between text-white">
                <a href="<?php echo BASE_URL; ?>/login" class="inline-flex items-center gap-2 text-sm font-bold text-blue-100 transition hover:text-white"><i class="bi bi-arrow-left"></i> Kembali ke login</a>
                <span class="hidden text-xs font-bold uppercase tracking-[.18em] text-orange-300 sm:inline">STMIK Triguna Dharma &middot; Medan</span>
            </div>
            <div class="grid overflow-hidden rounded-[2rem] bg-white shadow-2xl lg:grid-cols-[.72fr_1.28fr]">
                <aside class="bg-[#064b7a] p-7 text-white sm:p-10">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500 text-xl shadow-lg shadow-orange-950/30"><i class="bi bi-person-plus-fill"></i></div>
                    <p class="mt-8 text-xs font-bold uppercase tracking-[.2em] text-orange-300">Pendaftaran Mahasiswa</p>
                    <h1 class="mt-3 text-3xl font-extrabold leading-tight">Mulai perjalanan akademikmu.</h1>
                    <p class="mt-4 text-sm leading-7 text-blue-100/80">Isi data dengan benar agar akun akademik dan data mahasiswa terhubung sejak awal.</p>
                    <div class="mt-10 space-y-5">
                        <div class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-extrabold">1</span><div><p class="font-bold">Isi data diri</p><p class="mt-1 text-xs leading-5 text-blue-100/70">Lengkapi identitas dan program studi.</p></div></div>
                        <div class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/15 text-sm font-extrabold">2</span><div><p class="font-bold">Buat akun</p><p class="mt-1 text-xs leading-5 text-blue-100/70">Gunakan email dan password yang aman.</p></div></div>
                        <div class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/15 text-sm font-extrabold">3</span><div><p class="font-bold">Masuk ke portal</p><p class="mt-1 text-xs leading-5 text-blue-100/70">Lihat KRS, nilai, IPS, IPK, dan transkrip.</p></div></div>
                    </div>
                </aside>
                <main class="p-6 sm:p-10">
                    <div class="mb-7"><p class="text-sm font-bold text-[#064b7a]">Formulir mahasiswa baru</p><h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Buat akun mahasiswa</h2><p class="mt-2 text-sm leading-6 text-slate-500">Data bertanda bintang wajib diisi.</p></div>
                    <?php if ($error): ?><div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i class="bi bi-exclamation-circle-fill mt-0.5"></i><span><?php echo e($error); ?></span></div><?php endif; ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>/register" class="space-y-5">
                        <?php echo csrf_field(); ?>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-4" aria-live="polite">
                            <div class="flex items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-[.16em] text-[#064b7a]">Kelengkapan formulir</p><p id="registrationStatus" class="mt-1 text-xs text-slate-500">Lengkapi data untuk melanjutkan.</p></div><strong id="registrationPercent" class="text-lg font-extrabold text-[#064b7a]">0%</strong></div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-white"><div id="registrationProgress" class="h-full w-0 rounded-full bg-orange-500 transition-all duration-300"></div></div>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2"><label for="nama" class="mb-2 block text-sm font-bold text-slate-700">Nama lengkap *</label><input id="nama" name="nama" value="<?php echo e($form['nama'] ?? ''); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                            <div><label for="nim" class="mb-2 block text-sm font-bold text-slate-700">NIM *</label><input id="nim" name="nim" value="<?php echo e($form['nim'] ?? ''); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                            <div><label for="angkatan" class="mb-2 block text-sm font-bold text-slate-700">Angkatan *</label><input id="angkatan" name="angkatan" type="number" min="2000" max="2100" value="<?php echo e($form['angkatan'] ?? date('Y')); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                            <div class="sm:col-span-2"><label for="program_studi_id" class="mb-2 block text-sm font-bold text-slate-700">Program studi *</label><select id="program_studi_id" name="program_studi_id" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"><option value="">Pilih program studi</option><?php foreach ($programStudi as $program): ?><option value="<?php echo e($program['id']); ?>" <?php echo (int)($form['program_studi_id'] ?? 0) === (int)$program['id'] ? 'selected' : ''; ?>><?php echo e($program['nama_prodi'] . ' (' . $program['jenjang'] . ')'); ?></option><?php endforeach; ?></select></div>
                            <div><label for="no_hp" class="mb-2 block text-sm font-bold text-slate-700">Nomor HP</label><input id="no_hp" name="no_hp" type="tel" value="<?php echo e($form['no_hp'] ?? ''); ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                            <div><label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email *</label><input id="email" name="email" type="email" value="<?php echo e($form['email'] ?? ''); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                            <div><label for="username" class="mb-2 block text-sm font-bold text-slate-700">Username *</label><input id="username" name="username" value="<?php echo e($form['username'] ?? ''); ?>" required autocomplete="username" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                            <div><label for="password" class="mb-2 block text-sm font-bold text-slate-700">Password *</label><input id="password" name="password" type="password" minlength="8" required autocomplete="new-password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"><div class="mt-2 flex items-center gap-2"><div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-200"><div id="passwordStrengthBar" class="h-full w-0 rounded-full transition-all"></div></div><span id="passwordStrength" class="w-16 text-right text-[10px] font-bold text-slate-400">Belum diisi</span></div></div>
                            <div class="sm:col-span-2"><label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi password *</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required autocomplete="new-password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-[#0b5d8f] focus:bg-white focus:ring-4 focus:ring-blue-600/10"></div>
                        </div>
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-orange-500 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600"><i class="bi bi-check2-circle"></i> Daftarkan akun mahasiswa</button>
                    </form>
                </main>
            </div>
        </div>
    </div>
    <script>
        const registrationForm = document.querySelector('form[action$="/register"]');
        const requiredFields = [...registrationForm.querySelectorAll('[required]')];
        const progressBar = document.getElementById('registrationProgress');
        const progressPercent = document.getElementById('registrationPercent');
        const progressStatus = document.getElementById('registrationStatus');
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('passwordStrengthBar');
        const strengthLabel = document.getElementById('passwordStrength');

        const updateRegistrationProgress = () => {
            const completed = requiredFields.filter((field) => field.value.trim() !== '').length;
            const percentage = Math.round((completed / requiredFields.length) * 100);
            progressBar.style.width = `${percentage}%`;
            progressPercent.textContent = `${percentage}%`;
            progressStatus.textContent = percentage === 100 ? 'Formulir siap dikirim.' : `${completed} dari ${requiredFields.length} data wajib sudah diisi.`;
        };

        const updatePasswordStrength = () => {
            const value = password.value;
            const score = [value.length >= 8, /[A-Z]/.test(value), /[0-9]/.test(value), /[^A-Za-z0-9]/.test(value)].filter(Boolean).length;
            const widths = ['0%', '25%', '50%', '75%', '100%'];
            const colors = ['bg-slate-200', 'bg-red-400', 'bg-amber-400', 'bg-blue-500', 'bg-green-500'];
            strengthBar.className = `h-full rounded-full transition-all ${colors[score]}`;
            strengthBar.style.width = widths[score];
            strengthLabel.textContent = ['Belum diisi', 'Lemah', 'Cukup', 'Bagus', 'Kuat'][score];
            strengthLabel.className = `w-16 text-right text-[10px] font-bold ${score >= 3 ? 'text-green-600' : 'text-slate-400'}`;
        };

        requiredFields.forEach((field) => field.addEventListener('input', updateRegistrationProgress));
        password.addEventListener('input', updatePasswordStrength);
        updateRegistrationProgress();
        updatePasswordStrength();
    </script>
</body>
</html>
