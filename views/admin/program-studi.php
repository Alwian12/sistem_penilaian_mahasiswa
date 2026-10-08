<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Program Studi</h1>
            <p class="text-slate-500">Kelola data program studi</p>
        </div>
    </div>

    <?php $success = flash('success'); if ($success): ?>
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e($success); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-xl bg-white p-5 shadow border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Tambah Program Studi</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/program-studi" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="create">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Prodi</label>
                    <input name="kode_prodi" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Prodi</label>
                    <input name="nama_prodi" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenjang</label>
                    <select name="jenjang" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="D3">D3</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <button type="submit" class="w-full rounded-lg bg-sky-600 px-4 py-2.5 text-white font-semibold hover:bg-sky-700">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow border border-slate-200 overflow-hidden">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Daftar Program Studi</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2">Kode</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Jenjang</th>
                            <th class="px-3 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-t border-slate-200">
                                <td class="px-3 py-2"><?php echo e($item['kode_prodi']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama_prodi']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['jenjang']); ?></td>
                                <td class="px-3 py-2">
                                    <span class="rounded-full px-2 py-1 text-xs <?php echo (int)$item['status'] === 1 ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-700'; ?>">
                                        <?php echo (int)$item['status'] === 1 ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
