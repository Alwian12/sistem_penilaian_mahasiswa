<?php

class KrsController
{
    private KrsModel $krsModel;
    private MahasiswaModel $mahasiswaModel;
    private MataKuliahModel $mataKuliahModel;
    private TahunAkademikModel $tahunAkademikModel;
    private DosenModel $dosenModel;

    public function __construct(PDO $pdo)
    {
        $this->krsModel = new KrsModel($pdo);
        $this->mahasiswaModel = new MahasiswaModel($pdo);
        $this->mataKuliahModel = new MataKuliahModel($pdo);
        $this->tahunAkademikModel = new TahunAkademikModel($pdo);
        $this->dosenModel = new DosenModel($pdo);
    }

    public function index(): void
    {
        requireAuth('admin');
        $items = $this->krsModel->getAll();
        $mahasiswa = $this->mahasiswaModel->getAll();
        $matakuliah = $this->mataKuliahModel->getAll();
        $tahun = $this->tahunAkademikModel->getAll();
        $dosen = $this->dosenModel->getAll();
        view('admin/krs', ['pageTitle' => 'KRS', 'items' => $items, 'mahasiswa' => $mahasiswa, 'matakuliah' => $matakuliah, 'tahun' => $tahun, 'dosen' => $dosen]);
    }

    public function create(): void
    {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/krs');
        }

        verify_csrf();

        $mahasiswaId = (int)($_POST['mahasiswa_id'] ?? 0);
        $matkulId = (int)($_POST['mata_kuliah_id'] ?? 0);
        $dosenId = (int)($_POST['dosen_id'] ?? 0);
        $semester = (int)($_POST['semester'] ?? 0);
        $tahunAkademikId = (int)($_POST['tahun_akademik_id'] ?? 0);

        if ($mahasiswaId <= 0 || $matkulId <= 0 || $dosenId <= 0 || $semester <= 0 || $tahunAkademikId <= 0) {
            flash('error', 'Semua data KRS wajib diisi.');
            redirect('/admin/krs');
        }

        if ($this->krsModel->findByMahasiswaMatkulSemester($mahasiswaId, $matkulId, $semester, $tahunAkademikId)) {
            flash('error', 'Mata kuliah sudah terdaftar pada semester dan tahun akademik yang sama.');
            redirect('/admin/krs');
        }

        $this->krsModel->create([
            'mahasiswa_id' => $mahasiswaId,
            'dosen_id' => $dosenId,
            'mata_kuliah_id' => $matkulId,
            'semester' => $semester,
            'tahun_akademik_id' => $tahunAkademikId,
            'status' => 1,
        ]);

        flash('success', 'KRS berhasil ditambahkan.');
        redirect('/admin/krs');
    }

    public function mandiri(): void
    {
        requireAuth('mahasiswa');

        $user = currentUser();
        $mahasiswa = $this->mahasiswaModel->findByUserId((int)($user['id'] ?? 0));

        if (!$mahasiswa) {
            flash('error', 'Data mahasiswa belum terdaftar. Hubungi admin.');
            redirect('/mahasiswa/dashboard');
        }

        $activeTahun = $this->tahunAkademikModel->getActive();
        if (!$activeTahun) {
            $allTahun = $this->tahunAkademikModel->getAll();
            $activeTahun = $allTahun[0] ?? null;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $mkIds = $_POST['mata_kuliah_ids'] ?? [];
            if (!is_array($mkIds) || empty($mkIds)) {
                flash('error', 'Pilih minimal satu mata kuliah untuk diambil.');
                redirect('/mahasiswa/krs');
            }

            if (!$activeTahun) {
                flash('error', 'Tahun akademik belum aktif.');
                redirect('/mahasiswa/krs');
            }

            $successCount = 0;
            $dosenProdiList = $this->dosenModel->getByProgramStudiId((int)$mahasiswa['program_studi_id']);
            $defaultDosenId = !empty($dosenProdiList) ? (int)$dosenProdiList[0]['id'] : null;

            foreach ($mkIds as $mkId) {
                $mkId = (int)$mkId;
                $mk = $this->mataKuliahModel->findById($mkId);
                if (!$mk) continue;

                $semester = (int)($mk['semester'] ?? 1);
                $tahunAkademikId = (int)$activeTahun['id'];

                if ($this->krsModel->findByMahasiswaMatkulSemester((int)$mahasiswa['id'], $mkId, $semester, $tahunAkademikId)) {
                    continue;
                }

                $dosenId = !empty($_POST['dosen_ids'][$mkId]) ? (int)$_POST['dosen_ids'][$mkId] : $defaultDosenId;

                $this->krsModel->create([
                    'mahasiswa_id' => (int)$mahasiswa['id'],
                    'dosen_id' => $dosenId,
                    'mata_kuliah_id' => $mkId,
                    'semester' => $semester,
                    'tahun_akademik_id' => $tahunAkademikId,
                    'status' => 1,
                ]);
                $successCount++;
            }

            if ($successCount > 0) {
                flash('success', "Berhasil mengambil {$successCount} mata kuliah pada KRS Anda.");
            } else {
                flash('error', 'Mata kuliah yang dipilih sudah ada di KRS Anda.');
            }

            redirect('/mahasiswa/krs');
        }

        $availableMk = $this->mataKuliahModel->getByProgramStudiId((int)$mahasiswa['program_studi_id']);
        $myKrs = $this->krsModel->getByMahasiswaId((int)$mahasiswa['id']);
        $dosenList = $this->dosenModel->getByProgramStudiId((int)$mahasiswa['program_studi_id']);

        $enrolledMkIds = [];
        if ($activeTahun) {
            foreach ($myKrs as $k) {
                if ((int)($k['tahun_akademik_id'] ?? 0) === (int)($activeTahun['id'] ?? 0)) {
                    $enrolledMkIds[] = (int)$k['mata_kuliah_id'];
                }
            }
        }

        view('mahasiswa/krs_mandiri', [
            'pageTitle' => 'Pengisian KRS Mandiri',
            'mahasiswa' => $mahasiswa,
            'activeTahun' => $activeTahun,
            'availableMk' => $availableMk,
            'myKrs' => $myKrs,
            'enrolledMkIds' => $enrolledMkIds,
            'dosenList' => $dosenList,
        ]);
    }

    public function delete(): void
    {
        requireAuth('admin');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/krs');
        }

        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->krsModel->delete($id);
            flash('success', 'Data KRS berhasil dihapus.');
        }
        redirect('/admin/krs');
    }
}
