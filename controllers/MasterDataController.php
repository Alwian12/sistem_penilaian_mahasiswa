<?php

class MasterDataController
{
    private PDO $pdo;
    private ProgramStudiModel $programStudiModel;
    private MahasiswaModel $mahasiswaModel;
    private DosenModel $dosenModel;
    private MataKuliahModel $mataKuliahModel;
    private TahunAkademikModel $tahunAkademikModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->programStudiModel = new ProgramStudiModel($pdo);
        $this->mahasiswaModel = new MahasiswaModel($pdo);
        $this->dosenModel = new DosenModel($pdo);
        $this->mataKuliahModel = new MataKuliahModel($pdo);
        $this->tahunAkademikModel = new TahunAkademikModel($pdo);
    }

    public function programStudi(): void
    {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $action = $_POST['action'] ?? 'create';
            $data = $_POST;

            if ($action === 'create') {
                $this->programStudiModel->create($data);
                flash('success', 'Data program studi berhasil ditambahkan.');
            } elseif ($action === 'update') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->programStudiModel->update($id, $data);
                    flash('success', 'Data program studi berhasil diperbarui.');
                }
            }

            redirect('/admin/program-studi');
        }

        $items = $this->programStudiModel->getAll();
        view('admin/program-studi', ['pageTitle' => 'Program Studi', 'items' => $items]);
    }

    public function mahasiswa(): void
    {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $action = $_POST['action'] ?? 'create';
            $data = $_POST;

            if ($action === 'create') {
                $this->mahasiswaModel->create($data);
                flash('success', 'Data mahasiswa berhasil ditambahkan.');
            } elseif ($action === 'update') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->mahasiswaModel->update($id, $data);
                    flash('success', 'Data mahasiswa berhasil diperbarui.');
                }
            }

            redirect('/admin/mahasiswa');
        }

        $items = $this->mahasiswaModel->getAll();
        $prodi = $this->programStudiModel->getAll();
        view('admin/mahasiswa', ['pageTitle' => 'Mahasiswa', 'items' => $items, 'prodi' => $prodi]);
    }

    public function dosen(): void
    {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $action = $_POST['action'] ?? 'create';
            $data = $_POST;

            if ($action === 'create') {
                $this->dosenModel->create($data);
                flash('success', 'Data dosen berhasil ditambahkan.');
            } elseif ($action === 'update') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->dosenModel->update($id, $data);
                    flash('success', 'Data dosen berhasil diperbarui.');
                }
            }

            redirect('/admin/dosen');
        }

        $items = $this->dosenModel->getAll();
        $prodi = $this->programStudiModel->getAll();
        view('admin/dosen', ['pageTitle' => 'Dosen', 'items' => $items, 'prodi' => $prodi]);
    }

    public function mataKuliah(): void
    {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $action = $_POST['action'] ?? 'create';
            $data = $_POST;

            if ($action === 'create') {
                $this->mataKuliahModel->create($data);
                flash('success', 'Data mata kuliah berhasil ditambahkan.');
            } elseif ($action === 'update') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->mataKuliahModel->update($id, $data);
                    flash('success', 'Data mata kuliah berhasil diperbarui.');
                }
            }

            redirect('/admin/mata-kuliah');
        }

        $items = $this->mataKuliahModel->getAll();
        $prodi = $this->programStudiModel->getAll();
        view('admin/mata-kuliah', ['pageTitle' => 'Mata Kuliah', 'items' => $items, 'prodi' => $prodi]);
    }

    public function tahunAkademik(): void
    {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $action = $_POST['action'] ?? 'create';
            $data = $_POST;

            if ($action === 'create') {
                $this->tahunAkademikModel->create($data);
                flash('success', 'Data tahun akademik berhasil ditambahkan.');
            } elseif ($action === 'update') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->tahunAkademikModel->update($id, $data);
                    flash('success', 'Data tahun akademik berhasil diperbarui.');
                }
            }

            redirect('/admin/tahun-akademik');
        }

        $items = $this->tahunAkademikModel->getAll();
        view('admin/tahun-akademik', ['pageTitle' => 'Tahun Akademik', 'items' => $items]);
    }
}
