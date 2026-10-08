<?php

class PerhitunganController
{
    private NilaiModel $nilaiModel;
    private MahasiswaModel $mahasiswaModel;

    public function __construct(PDO $pdo)
    {
        $this->nilaiModel = new NilaiModel($pdo);
        $this->mahasiswaModel = new MahasiswaModel($pdo);
    }

    public function ips(): void
    {
        requireAuth(['admin', 'dosen', 'mahasiswa']);
        [$mahasiswa, $mahasiswaId] = $this->resolveMahasiswaScope();
        $rows = $mahasiswaId > 0 ? $this->nilaiModel->getIpsPerSemester($mahasiswaId) : [];

        view('admin/ips', [
            'pageTitle' => 'IPS Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'selectedMahasiswaId' => $mahasiswaId,
            'rows' => $rows,
        ]);
    }

    public function ipk(): void
    {
        requireAuth(['admin', 'dosen', 'mahasiswa']);
        [$mahasiswa, $mahasiswaId] = $this->resolveMahasiswaScope();
        $ipk = $mahasiswaId > 0 ? $this->nilaiModel->getIpk($mahasiswaId) : 0.0;

        view('admin/ipk', [
            'pageTitle' => 'IPK Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'selectedMahasiswaId' => $mahasiswaId,
            'ipk' => $ipk,
        ]);
    }

    public function transkrip(): void
    {
        requireAuth(['admin', 'dosen', 'mahasiswa']);
        [$mahasiswa, $mahasiswaId] = $this->resolveMahasiswaScope();
        $items = $mahasiswaId > 0 ? $this->nilaiModel->getTranskripByMahasiswaId($mahasiswaId) : [];
        $ipk = $mahasiswaId > 0 ? $this->nilaiModel->getIpk($mahasiswaId) : 0.0;

        view('admin/transkrip', [
            'pageTitle' => 'Transkrip Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'selectedMahasiswaId' => $mahasiswaId,
            'items' => $items,
            'ipk' => $ipk,
        ]);
    }

    private function resolveMahasiswaScope(): array
    {
        $role = (string)(currentUser()['role'] ?? '');
        if ($role === 'mahasiswa') {
            $student = $this->mahasiswaModel->findByUserId((int)(currentUser()['id'] ?? 0));
            return [$student ? [$student] : [], $student ? (int)$student['id'] : 0];
        }

        $mahasiswa = $this->mahasiswaModel->getAll();
        return [$mahasiswa, (int)($_GET['mahasiswa_id'] ?? 0)];
    }
}
