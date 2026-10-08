<?php

class DosenModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = 'SELECT d.*, p.nama_prodi, p.kode_prodi
                FROM dosen d
                LEFT JOIN program_studi p ON p.id = d.program_studi_id
                ORDER BY d.nama ASC';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM dosen WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getByProgramStudiId(int $prodiId): array
    {
        $sql = 'SELECT d.*, p.nama_prodi
                FROM dosen d
                LEFT JOIN program_studi p ON p.id = d.program_studi_id
                WHERE d.program_studi_id = :prodi_id AND d.status = 1
                ORDER BY d.nama ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['prodi_id' => $prodiId]);
        return $stmt->fetchAll();
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT d.*, p.nama_prodi, p.kode_prodi
                FROM dosen d
                LEFT JOIN program_studi p ON p.id = d.program_studi_id
                WHERE d.user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO dosen (nidn, nama, email, no_hp, program_studi_id, status, user_id)
                VALUES (:nidn, :nama, :email, :no_hp, :program_studi_id, :status, :user_id)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nidn' => trim($data['nidn'] ?? ''),
            'nama' => trim($data['nama'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'no_hp' => trim($data['no_hp'] ?? ''),
            'program_studi_id' => (int)($data['program_studi_id'] ?? 0),
            'status' => (int)($data['status'] ?? 1),
            'user_id' => !empty($data['user_id']) ? (int)$data['user_id'] : null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE dosen SET nidn = :nidn, nama = :nama, email = :email, no_hp = :no_hp, program_studi_id = :program_studi_id, status = :status WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nidn' => trim($data['nidn'] ?? ''),
            'nama' => trim($data['nama'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'no_hp' => trim($data['no_hp'] ?? ''),
            'program_studi_id' => (int)($data['program_studi_id'] ?? 0),
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM dosen WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
