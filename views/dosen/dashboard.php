<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8">
        <p class="mb-2 text-xs font-bold uppercase tracking-[.18em] text-[#064b7a]">Ruang dosen</p>
        <h1 class="text-3xl font-extrabold tracking-tight text-ink">Dashboard Dosen</h1>
        <p class="mt-2 text-sm text-slate-500">Selamat datang, <?php echo e($_SESSION['user']['nama'] ?? 'Dosen'); ?>. Pantau penilaian yang sudah Anda input.</p>
    </div>

    <?php if ($dosen): ?>
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between"><p class="text-sm font-semibold text-slate-500">NIDN</p><i class="bi bi-person-badge text-xl text-[#0b5d8f]"></i></div>
                <h3 class="mt-3 text-2xl font-extrabold text-ink"><?php echo e($dosen['nidn']); ?></h3>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between"><p class="text-sm font-semibold text-slate-500">Program Studi</p><i class="bi bi-diagram-3 text-xl text-sky-600"></i></div>
                <h3 class="mt-3 text-2xl font-extrabold text-ink"><?php echo e($dosen['nama_prodi'] ?? '-'); ?></h3>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between"><p class="text-sm font-semibold text-slate-500">Total Nilai Dimasukkan</p><i class="bi bi-check2-circle text-xl text-amber-600"></i></div>
                <h3 id="liveTotalNilai" class="mt-3 text-2xl font-extrabold text-ink"><?php echo e(count($nilai)); ?></h3>
            </div>
        </div>

        <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="mb-4 flex items-center justify-between"><div><h2 class="text-lg font-extrabold text-ink">Daftar Mahasiswa Penguji</h2><p class="mt-1 text-sm text-slate-500">Mahasiswa yang ditugaskan ke akun dosen ini.</p></div><i class="bi bi-people text-2xl text-[#0b5d8f]"></i></div>
            <div id="liveAssignedStudents" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($mahasiswaUji as $item): ?>
                    <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4"><p class="text-sm font-extrabold text-[#064b7a]"><?php echo e($item['nim']); ?></p><p class="mt-1 text-sm text-slate-700"><?php echo e($item['nama_mahasiswa']); ?></p><p class="mt-2 text-xs text-slate-500"><?php echo e($item['kode_mk'] . ' - ' . $item['nama_mk']); ?></p></div>
                <?php endforeach; ?>
                <?php if (empty($mahasiswaUji)): ?><p class="text-sm text-slate-500">Belum ada mahasiswa yang ditugaskan.</p><?php endif; ?>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="mb-4 flex items-center justify-between"><div><h2 class="text-lg font-extrabold text-ink">Riwayat Penilaian</h2><p class="mt-1 text-sm text-slate-500">Data nilai diperbarui otomatis setiap beberapa detik.</p></div><span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-green-700"><span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span><span id="liveStatus">Live</span></span></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-3 py-2">Mahasiswa</th>
                            <th class="px-3 py-2">Mata Kuliah</th>
                            <th class="px-3 py-2">Semester</th>
                            <th class="px-3 py-2">Nilai</th>
                            <th class="px-3 py-2">Huruf</th>
                        </tr>
                    </thead>
                    <tbody id="liveNilaiTable">
                        <?php foreach ($nilai as $item): ?>
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-3 py-2"><?php echo e($item['nim'] . ' / ' . $item['nama_mahasiswa']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['kode_mk'] . ' - ' . $item['nama_mk']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['semester']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_angka']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_huruf']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($nilai)): ?>
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada nilai yang Anda input.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="mt-3 text-right text-xs text-slate-400">Pembaruan terakhir: <span id="liveUpdatedAt"><?php echo date('H:i:s'); ?></span></p>
        </div>
    <?php else: ?>
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
            Data dosen untuk akun Anda belum terhubung. Hubungi admin untuk mengaitkan akun dengan data dosen.
        </div>
    <?php endif; ?>
</div>
<?php if ($dosen): ?>
    <script>
        const liveEndpoint = <?php echo json_encode(BASE_URL . '/dosen/dashboard/live'); ?>;
        const liveTable = document.getElementById('liveNilaiTable');
        const liveTotal = document.getElementById('liveTotalNilai');
        const liveStatus = document.getElementById('liveStatus');
        const liveUpdatedAt = document.getElementById('liveUpdatedAt');
        const liveAssignedStudents = document.getElementById('liveAssignedStudents');

        const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[char]));
        const renderLiveRows = (items) => {
            if (!items.length) {
                liveTable.innerHTML = '<tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada mahasiswa yang Anda uji.</td></tr>';
                return;
            }
            liveTable.innerHTML = items.map((item) => `<tr class="border-t border-slate-100 hover:bg-slate-50"><td class="px-3 py-2">${escapeHtml(item.nim)} / ${escapeHtml(item.nama_mahasiswa)}</td><td class="px-3 py-2">${escapeHtml(item.kode_mk)} - ${escapeHtml(item.nama_mk)}</td><td class="px-3 py-2">${escapeHtml(item.semester)}</td><td class="px-3 py-2">${escapeHtml(item.nilai_angka)}</td><td class="px-3 py-2">${escapeHtml(item.nilai_huruf)}</td></tr>`).join('');
        };
        const renderAssignedStudents = (items) => {
            if (!items.length) {
                liveAssignedStudents.innerHTML = '<p class="text-sm text-slate-500">Belum ada mahasiswa yang ditugaskan.</p>';
                return;
            }
            liveAssignedStudents.innerHTML = items.map((item) => `<div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4"><p class="text-sm font-extrabold text-[#064b7a]">${escapeHtml(item.nim)}</p><p class="mt-1 text-sm text-slate-700">${escapeHtml(item.nama_mahasiswa)}</p><p class="mt-2 text-xs text-slate-500">${escapeHtml(item.kode_mk)} - ${escapeHtml(item.nama_mk)}</p></div>`).join('');
        };
        const refreshLiveData = async () => {
            try {
                const response = await fetch(`${liveEndpoint}?t=${Date.now()}`, { credentials: 'same-origin', cache: 'no-store' });
                if (!response.ok) throw new Error('live data unavailable');
                const data = await response.json();
                liveTotal.textContent = data.total;
                renderLiveRows(data.items);
                renderAssignedStudents(data.mahasiswa);
                liveUpdatedAt.textContent = data.updated_at;
                liveStatus.textContent = 'Live';
            } catch (error) {
                liveStatus.textContent = 'Terputus';
            }
        };
        window.setInterval(refreshLiveData, 10000);
    </script>
<?php endif; ?>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
