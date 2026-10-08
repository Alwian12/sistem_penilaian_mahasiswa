<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php
    $chartLabels = array_map(static fn(array $row): string => $row['tahun_akademik'] . ' / Sem ' . $row['semester'], $ips);
    $chartIps = array_map(static fn(array $row): float => (float)$row['ips'], $ips);
    $chartIpk = array_fill(0, count($chartLabels), (float)$ipk);
?>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <div class="flex items-center gap-2"><p class="text-xs font-bold uppercase tracking-[.18em] text-[#064b7a]">Ruang mahasiswa</p><span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-extrabold text-green-700"><span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span><span id="mhsLiveStatus">Live</span></span></div>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-ink">Dashboard Mahasiswa</h1>
            <p class="mt-2 text-sm text-slate-500">Selamat datang, <?php echo e($_SESSION['user']['nama'] ?? 'Mahasiswa'); ?>. Berikut ringkasan akademik Anda secara real-time.</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm"><i class="bi bi-clock text-[#0b5d8f]"></i>Pembaruan: <span id="mhsLiveClock"><?php echo date('H:i:s'); ?></span></div>
    </div>

    <?php if ($mahasiswa): ?>
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between"><p class="text-sm font-semibold text-slate-500">NIM</p><i class="bi bi-person-vcard text-xl text-[#0b5d8f]"></i></div>
                <h3 class="mt-3 text-2xl font-extrabold text-ink"><?php echo e($mahasiswa['nim']); ?></h3>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex items-center justify-between"><p class="text-sm font-semibold text-slate-500">Prodi</p><i class="bi bi-diagram-3 text-xl text-sky-600"></i></div>
                <h3 class="mt-3 text-2xl font-extrabold text-ink"><?php echo e($mahasiswa['nama_prodi'] ?? '-'); ?></h3>
            </div>
            <div class="rounded-2xl border border-orange-100 bg-orange-50 p-5 shadow-soft">
                <div class="flex items-center justify-between"><p class="text-sm font-semibold text-orange-700">IPK Kumulatif</p><i class="bi bi-speedometer2 text-xl text-orange-700"></i></div>
                <h3 id="mhsLiveIpk" class="mt-3 text-3xl font-extrabold text-orange-900"><?php echo e($ipk); ?></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="mb-4 flex items-center justify-between"><h2 class="text-lg font-extrabold text-ink">Status Akademik</h2><i class="bi bi-person-check text-2xl text-[#0b5d8f]"></i></div>
                <div class="space-y-3 text-sm text-slate-600">
                    <div class="flex justify-between"><span>Nama</span><strong class="text-slate-800"><?php echo e($mahasiswa['nama']); ?></strong></div>
                    <div class="flex justify-between"><span>Email</span><strong class="text-slate-800"><?php echo e($mahasiswa['email'] ?? '-'); ?></strong></div>
                    <div class="flex justify-between"><span>No HP</span><strong class="text-slate-800"><?php echo e($mahasiswa['no_hp'] ?? '-'); ?></strong></div>
                    <div class="flex justify-between"><span>Angkatan</span><strong class="text-slate-800"><?php echo e($mahasiswa['angkatan'] ?? '-'); ?></strong></div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="mb-4 flex items-center justify-between"><div><h2 class="text-lg font-extrabold text-ink">Performa Akademik</h2><p class="mt-1 text-xs text-slate-500">IPS semester dan garis IPK kumulatif.</p></div><i class="bi bi-bar-chart-line text-2xl text-[#0b5d8f]"></i></div>
                <div id="chartContainer" class="relative h-64"><canvas id="academicChart" aria-label="Grafik IPS dan IPK"></canvas></div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="mb-4 flex items-center justify-between"><div><h2 class="text-lg font-extrabold text-ink">Transkrip Terbaru</h2><p class="mt-1 text-sm text-slate-500">Riwayat nilai yang sudah tervalidasi.</p></div><i class="bi bi-file-earmark-text text-2xl text-[#0b5d8f]"></i></div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-3 py-2">Mata Kuliah</th>
                            <th class="px-3 py-2">SKS</th>
                            <th class="px-3 py-2">Semester</th>
                            <th class="px-3 py-2">Nilai</th>
                            <th class="px-3 py-2">Huruf</th>
                        </tr>
                    </thead>
                    <tbody id="mhsLiveTranskripTable">
                        <?php foreach ($transkrip as $item): ?>
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-3 py-2"><?php echo e($item['nama_mk']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['sks']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['semester']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_angka']); ?></td>
                                <td class="px-3 py-2"><?php echo e($item['nilai_huruf']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($transkrip)): ?>
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada data nilai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
            Data mahasiswa untuk akun Anda belum terhubung. Hubungi admin untuk mengaitkan akun dengan data mahasiswa.
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const mhsLiveEndpoint = <?php echo json_encode(BASE_URL . '/mahasiswa/dashboard/live'); ?>;
    const mhsLiveIpk = document.getElementById('mhsLiveIpk');
    const mhsLiveClock = document.getElementById('mhsLiveClock');
    const mhsLiveStatus = document.getElementById('mhsLiveStatus');
    const mhsTableBody = document.getElementById('mhsLiveTranskripTable');

    let chartInstance = null;
    const academicChartCtx = document.getElementById('academicChart');

    const escapeHtml = (val) => String(val ?? '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));

    const initOrUpdateChart = (ipsData, currentIpk) => {
        if (!academicChartCtx) return;
        const labels = ipsData.map(r => `${r.tahun_akademik} / Sem ${r.semester}`);
        const ipsValues = ipsData.map(r => parseFloat(r.ips) || 0);
        const ipkValues = new Array(labels.length).fill(parseFloat(currentIpk) || 0);

        if (chartInstance) {
            chartInstance.data.labels = labels;
            chartInstance.data.datasets[0].data = ipsValues;
            chartInstance.data.datasets[1].data = ipkValues;
            chartInstance.update();
        } else {
            chartInstance = new Chart(academicChartCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'IPS', data: ipsValues, borderColor: '#0b5d8f', backgroundColor: 'rgba(11, 93, 143, .12)', fill: true, tension: .35, pointRadius: 4, pointBackgroundColor: '#f97316' },
                        { label: 'IPK', data: ipkValues, borderColor: '#f97316', borderDash: [6, 5], fill: false, tension: 0, pointRadius: 0 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, scales: { y: { min: 0, max: 4, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }, plugins: { legend: { labels: { usePointStyle: true, boxWidth: 8 } } } }
            });
        }
    };

    // Initialize chart immediately
    initOrUpdateChart(<?php echo json_encode($ips); ?>, <?php echo json_encode($ipk); ?>);

    const refreshMhsLive = async () => {
        try {
            const res = await fetch(`${mhsLiveEndpoint}?t=${Date.now()}`, { cache: 'no-store' });
            if (!res.ok) throw new Error('Live fail');
            const data = await res.json();
            if (data.ipk !== undefined && mhsLiveIpk) {
                mhsLiveIpk.textContent = data.ipk;
            }
            if (data.transkrip && mhsTableBody) {
                if (data.transkrip.length === 0) {
                    mhsTableBody.innerHTML = '<tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Belum ada data nilai.</td></tr>';
                } else {
                    mhsTableBody.innerHTML = data.transkrip.map(item => `
                        <tr class="border-t border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-2">${escapeHtml(item.nama_mk)}</td>
                            <td class="px-3 py-2">${escapeHtml(item.sks)}</td>
                            <td class="px-3 py-2">${escapeHtml(item.semester)}</td>
                            <td class="px-3 py-2">${escapeHtml(item.nilai_angka)}</td>
                            <td class="px-3 py-2">${escapeHtml(item.nilai_huruf)}</td>
                        </tr>
                    `).join('');
                }
            }
            if (data.ips) {
                initOrUpdateChart(data.ips, data.ipk);
            }
            if (data.updated_at && mhsLiveClock) {
                mhsLiveClock.textContent = data.updated_at;
            }
            if (mhsLiveStatus) mhsLiveStatus.textContent = 'Live';
        } catch (e) {
            if (mhsLiveStatus) mhsLiveStatus.textContent = 'Offline';
        }
    };

    window.setInterval(refreshMhsLive, 6000);
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
