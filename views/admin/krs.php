<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">KRS</h1>
        <p class="text-slate-500">Kelola KRS mahasiswa</p>
    </div>

    <?php $success = flash('success'); if ($success): ?>
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e($success); ?></div>
    <?php endif; ?>

    <?php $error = flash('error'); if ($error): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($error); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-xl bg-white p-5 shadow border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Tambah KRS</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/krs/create" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Dosen Penguji</label>
                    <select name="dosen_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="">Pilih Dosen Penguji</option>
                        <?php foreach ($dosen as $d): ?>
                            <option value="<?php echo e($d['id']); ?>"><?php echo e($d['nidn'] . ' - ' . $d['nama']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mahasiswa</label>
                    <select name="mahasiswa_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <?php foreach ($mahasiswa as $m): ?>
                            <option value="<?php echo e($m['id']); ?>"><?php echo e($m['nim'] . ' - ' . $m['nama']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mata Kuliah</label>
                    <select name="mata_kuliah_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <?php foreach ($matakuliah as $mk): ?>
                            <option value="<?php echo e($mk['id']); ?>"><?php echo e($mk['kode_mk'] . ' - ' . $mk['nama_mk']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Akademik</label>
                    <select name="tahun_akademik_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <?php foreach ($tahun as $ta): ?>
                            <option value="<?php echo e($ta['id']); ?>"><?php echo e($ta['tahun_akademik'] . ' - ' . $ta['semester']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Semester</label>
                    <input name="semester" type="number" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <button type="submit" class="w-full rounded-lg bg-sky-600 px-4 py-2.5 text-white font-semibold hover:bg-sky-700">Simpan KRS</button>
            </form>
        </div>

        <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow border border-slate-200 overflow-hidden">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-800">Daftar KRS</h2>
                <div class="relative w-full sm:w-64">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input id="searchKrsInput" type="text" placeholder="Cari nim, mahasiswa, matkul, dosen..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-1.5 pl-8 pr-3 text-xs focus:border-sky-500 focus:bg-white focus:outline-none">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="tableKrs" class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2">Mahasiswa</th>
                            <th class="px-3 py-2">Mata Kuliah</th>
                            <th class="px-3 py-2">Dosen Penguji</th>
                            <th class="px-3 py-2">Semester</th>
                            <th class="px-3 py-2">Tahun</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-t border-slate-200">
                                <td class="px-3 py-2"><?php echo e($item['nim'] ?? '-'); ?> / <?php echo e($item['nama_mahasiswa'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['kode_mk'] ?? '-') . ' - ' . e($item['nama_mk'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama_dosen'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['semester']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['tahun_akademik'] ?? '-'); ?></td>
                                <td class="px-3 py-2">
                                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/krs/delete" onsubmit="return confirm('Hapus KRS ini?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($item['id']); ?>">
                                        <button type="submit" class="rounded bg-red-500 px-2 py-1 text-white text-xs hover:bg-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    const searchKrs = document.getElementById('searchKrsInput');
    const rowsKrs = document.querySelectorAll('#tableKrs tbody tr');
    if (searchKrs && rowsKrs) {
        searchKrs.addEventListener('input', () => {
            const q = searchKrs.value.toLowerCase().trim();
            rowsKrs.forEach(r => { r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none'; });
        });
    }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
