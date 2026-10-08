<?php

class MahasiswaController
{
    private MahasiswaModel $mahasiswaModel;
    private NilaiModel $nilaiModel;

    public function __construct(PDO $pdo)
    {
        $this->mahasiswaModel = new MahasiswaModel($pdo);
        $this->nilaiModel = new NilaiModel($pdo);
    }

    public function dashboard(): void
    {
        requireAuth('mahasiswa');

        $user = currentUser();
        $mahasiswa = $this->mahasiswaModel->findByUserId((int)($user['id'] ?? 0));
        $transkrip = $mahasiswa ? $this->nilaiModel->getTranskripByMahasiswaId((int)$mahasiswa['id']) : [];
        $ips = $mahasiswa ? $this->nilaiModel->getIpsPerSemester((int)$mahasiswa['id']) : [];
        $ipk = $mahasiswa ? $this->nilaiModel->getIpk((int)$mahasiswa['id']) : 0.0;

        view('mahasiswa/dashboard', [
            'pageTitle' => 'Dashboard Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'transkrip' => $transkrip,
            'ips' => $ips,
            'ipk' => $ipk,
        ]);
    }

    public function liveData(): void
    {
        requireAuth('mahasiswa');

        $user = currentUser();
        $mahasiswa = $this->mahasiswaModel->findByUserId((int)($user['id'] ?? 0));
        $transkrip = $mahasiswa ? $this->nilaiModel->getTranskripByMahasiswaId((int)$mahasiswa['id']) : [];
        $ips = $mahasiswa ? $this->nilaiModel->getIpsPerSemester((int)$mahasiswa['id']) : [];
        $ipk = $mahasiswa ? $this->nilaiModel->getIpk((int)$mahasiswa['id']) : 0.0;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'updated_at' => date('H:i:s'),
            'ipk' => $ipk,
            'ips' => $ips,
            'transkrip' => $transkrip,
        ], JSON_UNESCAPED_SLASHES);
    }
}
