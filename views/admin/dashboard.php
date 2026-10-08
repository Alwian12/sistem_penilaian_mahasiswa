<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="flex items-center gap-2"><p class="text-xs font-bold uppercase tracking-[.18em] text-[#064b7a]">Ringkasan sistem</p><span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-extrabold text-green-700"><span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span><span id="adminLiveStatus">Live</span></span></div>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-ink">Dashboard Admin</h1>
            <p class="mt-2 text-sm text-slate-500">Selamat datang, <?php echo e($_SESSION['user']['nama'] ?? 'Admin'); ?>. Pantau data akademik dari satu tempat secara real-time.</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm"><i class="bi bi-calendar3 text-[#0b5d8f]"></i><?php echo date('d M Y'); ?> &middot; <span id="adminLiveClock"><?php echo date('H:i:s'); ?></span></div>
    </div>

    <?php $counts = $counts ?? []; ?>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="flex items-start justify-between"><div><p class="text-sm font-semibold text-slate-500">Total Mahasiswa</p><h3 id="countMahasiswa" class="mt-3 text-3xl font-extrabold text-ink"><?php echo e($counts['mahasiswa'] ?? 0); ?></h3></div><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-xl text-[#0b5d8f]"><i class="bi bi-people-fill"></i></span></div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="flex items-start justify-between"><div><p class="text-sm font-semibold text-slate-500">Total Dosen</p><h3 id="countDosen" class="mt-3 text-3xl font-extrabold text-ink"><?php echo e($counts['dosen'] ?? 0); ?></h3></div><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-xl text-sky-700"><i class="bi bi-person-workspace"></i></span></div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="flex items-start justify-between"><div><p class="text-sm font-semibold text-slate-500">Total Mata Kuliah</p><h3 id="countMatkul" class="mt-3 text-3xl font-extrabold text-ink"><?php echo e($counts['mata_kuliah'] ?? 0); ?></h3></div><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-xl text-amber-600"><i class="bi bi-journal-bookmark-fill"></i></span></div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="flex items-start justify-between"><div><p class="text-sm font-semibold text-slate-500">Total Program Studi</p><h3 id="countProdi" class="mt-3 text-3xl font-extrabold text-ink"><?php echo e($counts['program_studi'] ?? 0); ?></h3></div><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-xl text-violet-700"><i class="bi bi-diagram-3-fill"></i></span></div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-white/70 p-8 text-center shadow-sm">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-400"><i class="bi bi-bar-chart-line"></i></div>
        <h2 class="mt-4 text-lg font-extrabold text-ink">Ringkasan akademik terhubung real-time</h2>
        <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">Gunakan menu di samping untuk mengisi data master, KRS, nilai, dan melihat perhitungan prestasi mahasiswa.</p>
    </div>
</div>
<script>
    const adminLiveEndpoint = <?php echo json_encode(BASE_URL . '/admin/dashboard/live'); ?>;
    const countMhs = document.getElementById('countMahasiswa');
    const countDsn = document.getElementById('countDosen');
    const countMk = document.getElementById('countMatkul');
    const countPr = document.getElementById('countProdi');
    const adminClock = document.getElementById('adminLiveClock');
    const adminStatus = document.getElementById('adminLiveStatus');

    const refreshAdminLive = async () => {
        try {
            const res = await fetch(`${adminLiveEndpoint}?t=${Date.now()}`, { cache: 'no-store' });
            if (!res.ok) throw new Error('Live fail');
            const data = await res.json();
            if (data.counts) {
                countMhs.textContent = data.counts.mahasiswa ?? countMhs.textContent;
                countDsn.textContent = data.counts.dosen ?? countDsn.textContent;
                countMk.textContent = data.counts.mata_kuliah ?? countMk.textContent;
                countPr.textContent = data.counts.program_studi ?? countPr.textContent;
            }
            if (data.updated_at) adminClock.textContent = data.updated_at;
            adminStatus.textContent = 'Live';
        } catch (e) {
            adminStatus.textContent = 'Offline';
        }
    };
    window.setInterval(refreshAdminLive, 5000);
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
