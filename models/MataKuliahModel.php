<?php

class MataKuliahModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = 'SELECT mk.*, p.nama_prodi
                FROM mata_kuliah mk
                LEFT JOIN program_studi p ON p.id = mk.program_studi_id
                ORDER BY mk.nama_mk ASC';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM mata_kuliah WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getByProgramStudiId(int $prodiId): array
    {
        $sql = 'SELECT mk.*, p.nama_prodi
                FROM mata_kuliah mk
                LEFT JOIN program_studi p ON p.id = mk.program_studi_id
                WHERE mk.program_studi_id = :prodi_id AND mk.status = 1
                ORDER BY mk.semester ASC, mk.nama_mk ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['prodi_id' => $prodiId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, jenis_mk, program_studi_id, status) VALUES (:kode_mk, :nama_mk, :sks, :semester, :jenis_mk, :program_studi_id, :status)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'kode_mk' => trim($data['kode_mk'] ?? ''),
            'nama_mk' => trim($data['nama_mk'] ?? ''),
            'sks' => (int)($data['sks'] ?? 0),
            'semester' => (int)($data['semester'] ?? 0),
            'jenis_mk' => trim($data['jenis_mk'] ?? ''),
            'program_studi_id' => (int)($data['program_studi_id'] ?? 0),
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE mata_kuliah SET kode_mk = :kode_mk, nama_mk = :nama_mk, sks = :sks, semester = :semester, jenis_mk = :jenis_mk, program_studi_id = :program_studi_id, status = :status WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'kode_mk' => trim($data['kode_mk'] ?? ''),
            'nama_mk' => trim($data['nama_mk'] ?? ''),
            'sks' => (int)($data['sks'] ?? 0),
            'semester' => (int)($data['semester'] ?? 0),
            'jenis_mk' => trim($data['jenis_mk'] ?? ''),
            'program_studi_id' => (int)($data['program_studi_id'] ?? 0),
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM mata_kuliah WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
