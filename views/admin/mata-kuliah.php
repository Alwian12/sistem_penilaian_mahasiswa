<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Mata Kuliah</h1>
        <p class="text-slate-500">Kelola data mata kuliah</p>
    </div>

    <?php $success = flash('success'); if ($success): ?>
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e($success); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-xl bg-white p-5 shadow border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Tambah Mata Kuliah</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/mata-kuliah" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="create">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode MK</label>
                    <input name="kode_mk" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama MK</label>
                    <input name="nama_mk" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">SKS</label>
                    <input name="sks" type="number" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Semester</label>
                    <input name="semester" type="number" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis MK</label>
                    <select name="jenis_mk" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="Wajib">Wajib</option>
                        <option value="Pilihan">Pilihan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Program Studi</label>
                    <select name="program_studi_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <?php foreach ($prodi as $item): ?>
                            <option value="<?php echo e($item['id']); ?>"><?php echo e($item['nama_prodi']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="w-full rounded-lg bg-sky-600 px-4 py-2.5 text-white font-semibold hover:bg-sky-700">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow border border-slate-200 overflow-hidden">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Daftar Mata Kuliah</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2">Kode</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">SKS</th>
                            <th class="px-3 py-2">Semester</th>
                            <th class="px-3 py-2">Prodi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-t border-slate-200">
                                <td class="px-3 py-2"><?php echo e($item['kode_mk']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama_mk']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['sks']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['semester']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama_prodi'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
