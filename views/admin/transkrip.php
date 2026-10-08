<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Transkrip Mahasiswa</h1>
        <p class="text-slate-500">Tampilan transkrip akademik lengkap beserta IPK.</p>
    </div>

    <div class="rounded-xl bg-white p-5 shadow border border-slate-200 mb-6">
        <form method="GET" action="<?php echo BASE_URL; ?>/admin/transkrip" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Mahasiswa</label>
                <select name="mahasiswa_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                    <option value="">Pilih Mahasiswa</option>
                    <?php foreach ($mahasiswa as $item): ?>
                        <option value="<?php echo e($item['id']); ?>" <?php echo (int)$selectedMahasiswaId === (int)$item['id'] ? 'selected' : ''; ?>><?php echo e($item['nim'] . ' - ' . $item['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-white font-semibold hover:bg-emerald-700">Lihat Transkrip</button>
        </form>
    </div>

    <?php if ($selectedMahasiswaId > 0): ?>
        <div class="rounded-xl bg-white border border-slate-200 shadow p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <p class="text-sm text-slate-500">IPK</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?php echo e($ipk); ?></h3>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative w-full sm:w-64">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input id="searchTranskripInput" type="text" placeholder="Filter mata kuliah / kode..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-1.5 pl-8 pr-3 text-xs focus:border-emerald-500 focus:bg-white focus:outline-none">
                    </div>
                    <div class="rounded bg-emerald-50 border border-emerald-200 px-3 py-2 text-sm text-emerald-700 font-semibold whitespace-nowrap">Transkrip Akademik</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="tableTranskrip" class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2">Kode MK</th>
                            <th class="px-3 py-2">Mata Kuliah</th>
                            <th class="px-3 py-2">SKS</th>
                            <th class="px-3 py-2">Semester</th>
                            <th class="px-3 py-2">Tahun</th>
                            <th class="px-3 py-2">Nilai</th>
                            <th class="px-3 py-2">Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-t border-slate-200">
                                <td class="px-3 py-2"><?php echo e($item['kode_mk']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama_mk']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['sks']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['semester']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['tahun_akademik']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_angka']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['bobot']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-slate-500">Belum ada transkrip untuk mahasiswa ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            const searchT = document.getElementById('searchTranskripInput');
            const rowsT = document.querySelectorAll('#tableTranskrip tbody tr');
            if (searchT && rowsT) {
                searchT.addEventListener('input', () => {
                    const q = searchT.value.toLowerCase().trim();
                    rowsT.forEach(r => { r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none'; });
                });
            }
        </script>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
