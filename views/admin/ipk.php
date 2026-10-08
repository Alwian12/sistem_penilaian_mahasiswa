<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">IPK Mahasiswa</h1>
        <p class="text-slate-500">Hitung dan tampilkan indeks prestasi kumulatif mahasiswa.</p>
    </div>

    <div class="rounded-xl bg-white p-5 shadow border border-slate-200 mb-6">
        <form method="GET" action="<?php echo BASE_URL; ?>/admin/ipk" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Mahasiswa</label>
                <select name="mahasiswa_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                    <option value="">Pilih Mahasiswa</option>
                    <?php foreach ($mahasiswa as $item): ?>
                        <option value="<?php echo e($item['id']); ?>" <?php echo (int)$selectedMahasiswaId === (int)$item['id'] ? 'selected' : ''; ?>><?php echo e($item['nim'] . ' - ' . $item['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2.5 text-white font-semibold hover:bg-violet-700">Lihat IPK</button>
        </form>
    </div>

    <?php if ($selectedMahasiswaId > 0): ?>
        <div class="rounded-xl bg-gradient-to-r from-violet-50 to-sky-50 border border-violet-200 p-8 shadow">
            <p class="text-sm uppercase tracking-wide text-violet-700 font-semibold">IPK</p>
            <h2 class="mt-3 text-5xl font-bold text-slate-800"><?php echo e($ipk); ?></h2>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
