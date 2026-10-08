<?php

class ProgramStudiModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM program_studi ORDER BY nama_prodi ASC';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM program_studi WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO program_studi (kode_prodi, nama_prodi, jenjang, status) VALUES (:kode_prodi, :nama_prodi, :jenjang, :status)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'kode_prodi' => trim($data['kode_prodi'] ?? ''),
            'nama_prodi' => trim($data['nama_prodi'] ?? ''),
            'jenjang' => trim($data['jenjang'] ?? ''),
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE program_studi SET kode_prodi = :kode_prodi, nama_prodi = :nama_prodi, jenjang = :jenjang, status = :status WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'kode_prodi' => trim($data['kode_prodi'] ?? ''),
            'nama_prodi' => trim($data['nama_prodi'] ?? ''),
            'jenjang' => trim($data['jenjang'] ?? ''),
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM program_studi WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
