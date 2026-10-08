<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Mahasiswa</h1>
        <p class="text-slate-500">Kelola data mahasiswa</p>
    </div>

    <?php $success = flash('success'); if ($success): ?>
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e($success); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-xl bg-white p-5 shadow border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Tambah Mahasiswa</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/mahasiswa" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="create">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">NIM</label>
                    <input name="nim" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                    <input name="nama" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
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
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">No HP</label>
                    <input name="no_hp" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Angkatan</label>
                    <input name="angkatan" type="number" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <button type="submit" class="w-full rounded-lg bg-sky-600 px-4 py-2.5 text-white font-semibold hover:bg-sky-700">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow border border-slate-200 overflow-hidden">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-800">Daftar Mahasiswa</h2>
                <div class="relative w-full sm:w-64">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input id="searchMhsInput" type="text" placeholder="Cari nim, nama, prodi..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-1.5 pl-8 pr-3 text-xs focus:border-sky-500 focus:bg-white focus:outline-none">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="tableMahasiswa" class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Prodi</th>
                            <th class="px-3 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-t border-slate-200">
                                <td class="px-3 py-2"><?php echo e($item['nim']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nama_prodi'] ?? '-'); ?></td>
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
<script>
    const searchMhs = document.getElementById('searchMhsInput');
    const rowsMhs = document.querySelectorAll('#tableMahasiswa tbody tr');
    if (searchMhs && rowsMhs) {
        searchMhs.addEventListener('input', () => {
            const q = searchMhs.value.toLowerCase().trim();
            rowsMhs.forEach(r => { r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none'; });
        });
    }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
