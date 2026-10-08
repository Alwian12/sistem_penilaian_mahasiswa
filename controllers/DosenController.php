<?php

class DosenController
{
    private DosenModel $dosenModel;
    private NilaiModel $nilaiModel;
    private KrsModel $krsModel;

    public function __construct(PDO $pdo)
    {
        $this->dosenModel = new DosenModel($pdo);
        $this->nilaiModel = new NilaiModel($pdo);
        $this->krsModel = new KrsModel($pdo);
    }

    public function dashboard(): void
    {
        requireAuth('dosen');

        $user = currentUser();
        $dosen = $this->dosenModel->findByUserId((int)($user['id'] ?? 0));
        $nilai = $dosen ? $this->nilaiModel->getByDosenScope((int)$dosen['id'], (int)$dosen['program_studi_id']) : [];
        $mahasiswaUji = $dosen ? $this->krsModel->getByDosenScope((int)$dosen['id'], (int)$dosen['program_studi_id']) : [];

        view('dosen/dashboard', [
            'pageTitle' => 'Dashboard Dosen',
            'dosen' => $dosen,
            'nilai' => $nilai,
            'mahasiswaUji' => $mahasiswaUji,
        ]);
    }

    public function liveData(): void
    {
        requireAuth('dosen');

        $user = currentUser();
        $dosen = $this->dosenModel->findByUserId((int)($user['id'] ?? 0));
        $nilai = $dosen ? $this->nilaiModel->getByDosenScope((int)$dosen['id'], (int)$dosen['program_studi_id']) : [];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'updated_at' => date('H:i:s'),
            'total' => count($nilai),
            'items' => $nilai,
            'mahasiswa' => $dosen ? $this->krsModel->getByDosenScope((int)$dosen['id'], (int)$dosen['program_studi_id']) : [],
        ], JSON_UNESCAPED_SLASHES);
    }
}
