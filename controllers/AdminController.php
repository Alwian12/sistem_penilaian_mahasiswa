<?php

class AdminController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function dashboard(): void
    {
        requireAuth('admin');

        $counts = [];
        foreach (['mahasiswa', 'dosen', 'mata_kuliah', 'program_studi'] as $table) {
            $counts[$table] = (int)$this->pdo->query('SELECT COUNT(*) FROM ' . $table . ' WHERE status = 1')->fetchColumn();
        }

        view('admin/dashboard', ['pageTitle' => 'Dashboard Admin', 'counts' => $counts]);
    }

    public function liveData(): void
    {
        requireAuth('admin');

        $counts = [];
        foreach (['mahasiswa', 'dosen', 'mata_kuliah', 'program_studi', 'krs', 'nilai'] as $table) {
            $counts[$table] = (int)$this->pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'updated_at' => date('H:i:s'),
            'counts' => $counts,
        ], JSON_UNESCAPED_SLASHES);
    }
}
