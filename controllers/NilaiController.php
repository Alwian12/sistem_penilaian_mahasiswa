<?php

class NilaiController
{
    private NilaiModel $nilaiModel;
    private KrsModel $krsModel;
    private MahasiswaModel $mahasiswaModel;
    private DosenModel $dosenModel;

    public function __construct(PDO $pdo)
    {
        $this->nilaiModel = new NilaiModel($pdo);
        $this->krsModel = new KrsModel($pdo);
        $this->mahasiswaModel = new MahasiswaModel($pdo);
        $this->dosenModel = new DosenModel($pdo);
    }

    public function index(): void
    {
        requireAuth(['admin', 'dosen']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            if (isset($_POST['bulk_grades']) && is_array($_POST['bulk_grades'])) {
                $countSuccess = 0;
                $currentRole = currentUser()['role'] ?? '';
                $dosenId = null;

                if ($currentRole === 'dosen') {
                    $dosen = $this->dosenModel->findByUserId((int)(currentUser()['id'] ?? 0));
                    $dosenId = $dosen ? (int)$dosen['id'] : null;
                } elseif (!empty($_POST['dosen_id'])) {
                    $dosenId = (int)$_POST['dosen_id'];
                }

                foreach ($_POST['bulk_grades'] as $krsId => $nilaiAngka) {
                    $krsId = (int)$krsId;
                    if ($krsId <= 0 || $nilaiAngka === '' || $nilaiAngka === null) {
                        continue;
                    }

                    $nilaiAngka = (float)$nilaiAngka;
                    if ($nilaiAngka < 0 || $nilaiAngka > 100) {
                        continue;
                    }

                    if ($currentRole === 'dosen') {
                        $dosenUser = $this->dosenModel->findByUserId((int)(currentUser()['id'] ?? 0));
                        if (!$dosenUser || !$this->krsModel->belongsToDosenScope($krsId, (int)$dosenUser['id'], (int)$dosenUser['program_studi_id'])) {
                            continue;
                        }
                    }

                    if ($this->nilaiModel->upsertFromKrs([
                        'krs_id' => $krsId,
                        'dosen_id' => $dosenId,
                        'nilai_angka' => $nilaiAngka,
                    ])) {
                        $countSuccess++;
                    }
                }

                if ($countSuccess > 0) {
                    flash('success', "Berhasil menyimpan {$countSuccess} nilai mahasiswa kelas.");
                } else {
                    flash('error', 'Tidak ada nilai yang diperbarui.');
                }

                redirect('/admin/nilai');
                return;
            }

            $krsId = (int)($_POST['krs_id'] ?? 0);
            $nilaiAngka = (float)($_POST['nilai_angka'] ?? 0);
            $dosenId = !empty($_POST['dosen_id']) ? (int)$_POST['dosen_id'] : null;

            if ($krsId <= 0 || $nilaiAngka < 0 || $nilaiAngka > 100) {
                flash('error', 'KRS dan nilai wajib diisi dengan rentang 0 sampai 100.');
                redirect('/admin/nilai');
            }

            if ((currentUser()['role'] ?? '') === 'dosen') {
                $dosen = $this->dosenModel->findByUserId((int)(currentUser()['id'] ?? 0));
                if (!$dosen || !$this->krsModel->belongsToDosenScope($krsId, (int)$dosen['id'], (int)$dosen['program_studi_id'])) {
                    http_response_code(403);
                    exit('KRS mahasiswa tidak ditugaskan kepada dosen ini.');
                }
                $dosenId = (int)$dosen['id'];
            }

            if (!$this->nilaiModel->upsertFromKrs([
                'krs_id' => $krsId,
                'dosen_id' => $dosenId,
                'nilai_angka' => $nilaiAngka,
            ])) {
                flash('error', 'Data nilai tidak valid. Pastikan KRS, dosen, dan rentang nilai sudah benar.');
                redirect('/admin/nilai');
            }

            flash('success', 'Data nilai berhasil disimpan.');
            redirect('/admin/nilai');
        }

        $dosen = $this->dosenModel->getAll();
        if ((currentUser()['role'] ?? '') === 'dosen') {
            $dosenUser = $this->dosenModel->findByUserId((int)(currentUser()['id'] ?? 0));
            $dosenId = $dosenUser ? (int)$dosenUser['id'] : 0;
            $programStudiId = $dosenUser ? (int)$dosenUser['program_studi_id'] : 0;
            $items = $dosenId > 0 ? $this->nilaiModel->getByDosenScope($dosenId, $programStudiId) : [];
            $krsList = $dosenId > 0 ? $this->krsModel->getByDosenScope($dosenId, $programStudiId) : [];
            $dosen = $dosenUser ? [$dosenUser] : [];
        } else {
            $items = $this->nilaiModel->getAll();
            $krsList = $this->krsModel->getAll();
        }
        $mahasiswa = $this->mahasiswaModel->getAll();

        view('admin/nilai', [
            'pageTitle' => 'Nilai Mahasiswa',
            'items' => $items,
            'krsList' => $krsList,
            'mahasiswa' => $mahasiswa,
            'dosen' => $dosen,
        ]);
    }
}
