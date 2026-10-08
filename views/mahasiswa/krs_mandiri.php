<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="mb-2 text-xs font-bold uppercase tracking-[.18em] text-[#064b7a]">Pengisian Kartu Rencana Studi</p>
            <h1 class="text-3xl font-extrabold tracking-tight text-ink">Pengisian KRS Mandiri</h1>
            <p class="mt-2 text-sm text-slate-500">Pilih mata kuliah yang akan Anda tempuh semester ini pada program studi <?php echo e($mahasiswa['nama_prodi'] ?? '-'); ?>.</p>
        </div>
        <?php if ($activeTahun): ?>
            <div class="inline-flex items-center gap-2 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-900 shadow-sm">
                <i class="bi bi-calendar-check text-orange-600"></i>
                <span>Tahun Akademik: <strong><?php echo e($activeTahun['tahun_akademik'] . ' (' . $activeTahun['semester'] . ')'); ?></strong></span>
            </div>
        <?php endif; ?>
    </div>

    <?php $success = flash('success'); if ($success): ?>
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">
            <i class="bi bi-check-circle-fill text-lg text-green-600"></i>
            <span><?php echo e($success); ?></span>
        </div>
    <?php endif; ?>

    <?php $error = flash('error'); if ($error): ?>
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">
            <i class="bi bi-exclamation-triangle-fill text-lg text-red-600"></i>
            <span><?php echo e($error); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Form Pilihan Matkul -->
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
                <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-ink">Pilihan Mata Kuliah Ditawarkan</h2>
                        <p class="mt-1 text-xs text-slate-500">Centang mata kuliah yang ingin Anda ambil untuk semester berjalan.</p>
                    </div>
                    <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-700">Prodi <?php echo e($mahasiswa['kode_prodi'] ?? 'TGD'); ?></span>
                </div>

                <?php if (empty($availableMk)): ?>
                    <div class="flex flex-col items-center justify-center p-8 text-center text-slate-500">
                        <i class="bi bi-journal-x text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm">Belum ada mata kuliah yang ditawarkan untuk program studi Anda.</p>
                    </div>
                <?php else: ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>/mahasiswa/krs">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-3">
                            <?php foreach ($availableMk as $mk):
                                $isEnrolled = in_array((int)$mk['id'], $enrolledMkIds, true);
                            ?>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-xl border <?php echo $isEnrolled ? 'border-green-200 bg-green-50/50' : 'border-slate-200 bg-slate-50/50 hover:bg-blue-50/30'; ?> p-4 transition">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="mata_kuliah_ids[]" value="<?php echo e($mk['id']); ?>" <?php echo $isEnrolled ? 'disabled checked' : ''; ?> class="mt-1 h-5 w-5 rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-xs font-bold text-[#064b7a]"><?php echo e($mk['kode_mk']); ?></span>
                                                <span class="rounded bg-slate-200/80 px-2 py-0.5 text-[10px] font-bold text-slate-700">Sem <?php echo e($mk['semester']); ?></span>
                                                <span class="rounded bg-orange-100 px-2 py-0.5 text-[10px] font-bold text-orange-800"><?php echo e($mk['sks']); ?> SKS</span>
                                            </div>
                                            <h3 class="mt-1 text-sm font-extrabold text-slate-800"><?php echo e($mk['nama_mk']); ?></h3>
                                        </div>
                                    </div>

                                    <?php if (!$isEnrolled && !empty($dosenList)): ?>
                                        <div class="min-w-[180px]">
                                            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Dosen Pengampu</label>
                                            <select name="dosen_ids[<?php echo e($mk['id']); ?>]" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-sky-500 focus:outline-none">
                                                <?php foreach ($dosenList as $d): ?>
                                                    <option value="<?php echo e($d['id']); ?>"><?php echo e($d['nama']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php elseif ($isEnrolled): ?>
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                                            <i class="bi bi-check-circle-fill"></i> Sudah diambil
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">
                                <i class="bi bi-journal-check text-lg"></i> Simpan KRS Mandiri
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar KRS Saya -->
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-soft">
                <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-base font-extrabold text-ink">KRS Saya Semester Ini</h2>
                    <i class="bi bi-bookmark-check text-xl text-[#0b5d8f]"></i>
                </div>

                <?php if (empty($myKrs)): ?>
                    <p class="text-xs text-slate-500 text-center py-6">Anda belum mengambil KRS di semester ini.</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php $totalSks = 0; foreach ($myKrs as $item): $totalSks += (int)($item['sks'] ?? 0); ?>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 text-xs">
                                <div class="flex items-center justify-between font-semibold text-slate-700">
                                    <span><?php echo e($item['kode_mk'] ?? '-'); ?></span>
                                    <span class="rounded bg-orange-100 px-2 py-0.5 text-[10px] font-bold text-orange-800"><?php echo e($item['sks'] ?? 0); ?> SKS</span>
                                </div>
                                <p class="mt-1 font-bold text-slate-800"><?php echo e($item['nama_mk'] ?? '-'); ?></p>
                                <p class="mt-1 text-[11px] text-slate-500">Sem <?php echo e($item['semester'] ?? '-'); ?> &middot; <?php echo e($item['tahun_akademik'] ?? '-'); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs font-bold text-slate-700">
                        <span>Total SKS Ditempuh:</span>
                        <span class="text-sm font-extrabold text-orange-600"><?php echo e($totalSks); ?> SKS</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
