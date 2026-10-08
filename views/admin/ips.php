<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">IPS Mahasiswa</h1>
        <p class="text-slate-500">Rekap indeks prestasi semester berdasarkan nilai yang telah diinput.</p>
    </div>

    <div class="rounded-xl bg-white p-5 shadow border border-slate-200 mb-6">
        <form method="GET" action="<?php echo BASE_URL; ?>/admin/ips" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Mahasiswa</label>
                <select name="mahasiswa_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                    <option value="">Pilih Mahasiswa</option>
                    <?php foreach ($mahasiswa as $item): ?>
                        <option value="<?php echo e($item['id']); ?>" <?php echo (int)$selectedMahasiswaId === (int)$item['id'] ? 'selected' : ''; ?>><?php echo e($item['nim'] . ' - ' . $item['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2.5 text-white font-semibold hover:bg-sky-700">Lihat IPS</button>
        </form>
    </div>

    <?php if ($selectedMahasiswaId > 0): ?>
        <div class="overflow-x-auto rounded-xl bg-white border border-slate-200 shadow">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-3 py-2">Semester</th>
                        <th class="px-3 py-2">Tahun Akademik</th>
                        <th class="px-3 py-2">Total SKS</th>
                        <th class="px-3 py-2">Total Mutu</th>
                        <th class="px-3 py-2">IPS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr class="border-t border-slate-200">
                            <td class="px-3 py-2"><?php echo e($row['semester']); ?></td>
                            <td class="px-3 py-2"><?php echo e($row['tahun_akademik']); ?></td>
                            <td class="px-3 py-2"><?php echo e($row['total_sks']); ?></td>
                            <td class="px-3 py-2"><?php echo e($row['total_mutu']); ?></td>
                            <td class="px-3 py-2 font-semibold"><?php echo e($row['ips']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada data nilai untuk mahasiswa ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
