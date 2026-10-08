<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Nilai Mahasiswa</h1>
            <p class="text-slate-500">Input dan rekap nilai serta perhitungan IPS/IPK</p>
        </div>
    </div>

    <?php $success = flash('success'); if ($success): ?>
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo e($success); ?></div>
    <?php endif; ?>

    <?php $error = flash('error'); if ($error): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($error); ?></div>
    <?php endif; ?>

    <!-- Penilaian Sekaligus per Kelas / Mata Kuliah -->
    <?php if (!empty($krsList)):
        $classes = [];
        foreach ($krsList as $k) {
            $mkKey = ($k['mata_kuliah_id'] ?? 0);
            if (!isset($classes[$mkKey])) {
                $classes[$mkKey] = [
                    'id' => $mkKey,
                    'kode_mk' => $k['kode_mk'] ?? '-',
                    'nama_mk' => $k['nama_mk'] ?? '-',
                    'students' => [],
                ];
            }
            $classes[$mkKey]['students'][] = $k;
        }
    ?>
    <div class="mb-8 rounded-xl bg-white p-6 shadow border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-people-fill text-orange-500"></i>
                    Penilaian Sekaligus per Kelas / Mata Kuliah
                </h2>
                <p class="text-xs text-slate-500">Pilih mata kuliah untuk memasukkan nilai seluruh mahasiswa di kelas sekaligus.</p>
            </div>
            <div>
                <select id="selectClassFilter" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold focus:border-orange-500 focus:outline-none">
                    <option value="">Pilih Kelas / Mata Kuliah</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?php echo e($c['id']); ?>"><?php echo e($c['kode_mk'] . ' - ' . $c['nama_mk'] . ' (' . count($c['students']) . ' Mahasiswa)'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php foreach ($classes as $mkId => $c): ?>
            <div id="classTable_<?php echo e($mkId); ?>" class="class-grading-table hidden">
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/nilai">
                    <?php echo csrf_field(); ?>
                    <?php if ((currentUser()['role'] ?? '') === 'dosen'): ?>
                        <input type="hidden" name="dosen_id" value="<?php echo e($dosen[0]['id'] ?? ''); ?>">
                    <?php endif; ?>
                    <div class="overflow-x-auto rounded-lg border border-slate-200 mb-4">
                        <table class="min-w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-700">
                                <tr>
                                    <th class="px-3 py-2.5">NIM</th>
                                    <th class="px-3 py-2.5">Nama Mahasiswa</th>
                                    <th class="px-3 py-2.5">Mata Kuliah</th>
                                    <th class="px-3 py-2.5 w-36">Nilai Angka (0-100)</th>
                                    <th class="px-3 py-2.5">Konversi Real-time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($c['students'] as $st):
                                    $existingGrade = null;
                                    foreach ($items as $item) {
                                        if ((int)($item['krs_id'] ?? 0) === (int)$st['id']) {
                                            $existingGrade = $item;
                                            break;
                                        }
                                    }
                                ?>
                                    <tr class="border-t border-slate-200">
                                        <td class="px-3 py-2 font-mono font-bold text-[#064b7a]"><?php echo e($st['nim']); ?></td>
                                        <td class="px-3 py-2 font-semibold text-slate-800"><?php echo e($st['nama_mahasiswa']); ?></td>
                                        <td class="px-3 py-2 text-slate-600"><?php echo e($st['kode_mk'] . ' - ' . $st['nama_mk']); ?></td>
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" max="100" step="0.01" name="bulk_grades[<?php echo e($st['id']); ?>]" value="<?php echo e($existingGrade['nilai_angka'] ?? ''); ?>" placeholder="0 - 100" class="bulk-grade-input w-full rounded border border-slate-300 px-2.5 py-1 text-xs focus:border-orange-500 focus:outline-none">
                                        </td>
                                        <td class="px-3 py-2">
                                            <span class="bulk-grade-preview font-bold text-slate-400">
                                                <?php if ($existingGrade): ?>
                                                    <?php echo e($existingGrade['nilai_huruf'] . ' (' . $existingGrade['bobot'] . ')'); ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-2.5 text-xs font-extrabold text-white shadow hover:bg-orange-600">
                            <i class="bi bi-check2-square"></i> Simpan Semua Nilai Kelas Ini
                        </button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-1 rounded-xl bg-white p-5 shadow border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Input Nilai</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/admin/nilai" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">KRS</label>
                    <select name="krs_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="">Pilih KRS</option>
                        <?php foreach ($krsList as $row): ?>
                            <option value="<?php echo e($row['id']); ?>"><?php echo e(($row['nim'] ?? '-') . ' - ' . ($row['nama_mahasiswa'] ?? '-') . ' | ' . ($row['kode_mk'] ?? '-') . ' - ' . ($row['nama_mk'] ?? '-')); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Dosen</label>
                    <select name="dosen_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                        <option value="">Pilih Dosen</option>
                        <?php foreach ($dosen as $row): ?>
                            <option value="<?php echo e($row['id']); ?>"><?php echo e($row['nama']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nilai Angka</label>
                    <input id="nilaiAngkaInput" name="nilai_angka" type="number" min="0" max="100" step="0.01" required placeholder="Contoh: 85.5" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:border-sky-500">
                </div>
                <!-- Real-time Live Grade Preview -->
                <div id="liveGradePreview" class="hidden rounded-xl border border-blue-100 bg-blue-50/70 p-3.5 text-xs space-y-2">
                    <p class="font-bold text-[#064b7a] flex items-center justify-between"><span>Konversi Real-time</span><span id="previewStatus" class="rounded-full px-2 py-0.5 font-bold">LULUS</span></p>
                    <div class="flex justify-between border-t border-blue-100 pt-2 text-slate-700">
                        <span>Huruf: <strong id="previewHuruf" class="text-base text-blue-900 font-extrabold">-</strong></span>
                        <span>Bobot: <strong id="previewBobot" class="text-base text-blue-900 font-extrabold">0.00</strong></span>
                    </div>
                </div>
                <button type="submit" class="w-full rounded-lg bg-sky-600 px-4 py-2.5 text-white font-semibold hover:bg-sky-700">Simpan Nilai</button>
            </form>
        </div>

        <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow border border-slate-200 overflow-hidden">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-800">Rekap Nilai</h2>
                <div class="relative w-full sm:w-64">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input id="tableSearchInput" type="text" placeholder="Cari nim, nama, matkul..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-1.5 pl-8 pr-3 text-xs focus:border-sky-500 focus:bg-white focus:outline-none">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="rekapNilaiTable" class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2">Mahasiswa</th>
                            <th class="px-3 py-2">Mata Kuliah</th>
                            <th class="px-3 py-2">Semester</th>
                            <th class="px-3 py-2">Nilai</th>
                            <th class="px-3 py-2">Huruf</th>
                            <th class="px-3 py-2">Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-t border-slate-200">
                                <td class="px-3 py-2"><?php echo e($item['nim'] ?? '-'); ?> / <?php echo e($item['nama_mahasiswa'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['kode_mk'] ?? '-'); ?> - <?php echo e($item['nama_mk'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['krs_semester'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_angka'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_huruf'] ?? '-'); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['bobot'] ?? '0'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    // Real-time Grade Converter Preview
    const inputVal = document.getElementById('nilaiAngkaInput');
    const previewBox = document.getElementById('liveGradePreview');
    const previewHuruf = document.getElementById('previewHuruf');
    const previewBobot = document.getElementById('previewBobot');
    const previewStatus = document.getElementById('previewStatus');

    const convertGrade = (val) => {
        if (val === '' || isNaN(val) || val < 0 || val > 100) return null;
        const n = parseFloat(val);
        if (n >= 85) return { huruf: 'A', bobot: '4.00', status: 'LULUS' };
        if (n >= 80) return { huruf: 'A-', bobot: '3.75', status: 'LULUS' };
        if (n >= 75) return { huruf: 'B+', bobot: '3.50', status: 'LULUS' };
        if (n >= 70) return { huruf: 'B', bobot: '3.00', status: 'LULUS' };
        if (n >= 65) return { huruf: 'B-', bobot: '2.75', status: 'LULUS' };
        if (n >= 60) return { huruf: 'C+', bobot: '2.50', status: 'LULUS' };
        if (n >= 55) return { huruf: 'C', bobot: '2.00', status: 'LULUS' };
        if (n >= 50) return { huruf: 'C-', bobot: '1.75', status: 'TIDAK LULUS' };
        if (n >= 40) return { huruf: 'D', bobot: '1.00', status: 'TIDAK LULUS' };
        return { huruf: 'E', bobot: '0.00', status: 'TIDAK LULUS' };
    };

    if (inputVal) {
        inputVal.addEventListener('input', () => {
            const res = convertGrade(inputVal.value);
            if (res) {
                previewBox.classList.remove('hidden');
                previewHuruf.textContent = res.huruf;
                previewBobot.textContent = res.bobot;
                previewStatus.textContent = res.status;
                previewStatus.className = `rounded-full px-2 py-0.5 font-bold ${res.status === 'LULUS' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
            } else {
                previewBox.classList.add('hidden');
            }
        });
    }

    // Real-time Table Search
    const searchInput = document.getElementById('tableSearchInput');
    const tableRows = document.querySelectorAll('#rekapNilaiTable tbody tr');

    if (searchInput && tableRows) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Class Filter & Bulk Grade Live Preview
    const selectClass = document.getElementById('selectClassFilter');
    const classTables = document.querySelectorAll('.class-grading-table');

    if (selectClass) {
        selectClass.addEventListener('change', () => {
            const selectedId = selectClass.value;
            classTables.forEach(t => t.classList.add('hidden'));
            if (selectedId) {
                const target = document.getElementById(`classTable_${selectedId}`);
                if (target) target.classList.remove('hidden');
            }
        });
    }

    document.querySelectorAll('.bulk-grade-input').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            const preview = row ? row.querySelector('.bulk-grade-preview') : null;
            if (!preview) return;
            const res = convertGrade(input.value);
            if (res) {
                preview.textContent = `${res.huruf} (${res.bobot}) - ${res.status}`;
                preview.className = `bulk-grade-preview font-bold ${res.status === 'LULUS' ? 'text-green-600' : 'text-red-600'}`;
            } else {
                preview.textContent = '-';
                preview.className = 'bulk-grade-preview font-bold text-slate-400';
            }
        });
    });
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
