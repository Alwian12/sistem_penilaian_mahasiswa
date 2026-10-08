<?php

class MahasiswaModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = 'SELECT m.*, p.nama_prodi, p.kode_prodi
                FROM mahasiswa m
                LEFT JOIN program_studi p ON p.id = m.program_studi_id
                ORDER BY m.nama ASC';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM mahasiswa WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT m.*, p.nama_prodi, p.kode_prodi
                FROM mahasiswa m
                LEFT JOIN program_studi p ON p.id = m.program_studi_id
                WHERE m.user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO mahasiswa (nim, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, email, no_hp, program_studi_id, angkatan, status, user_id)
                VALUES (:nim, :nama, :jenis_kelamin, :tempat_lahir, :tanggal_lahir, :alamat, :email, :no_hp, :program_studi_id, :angkatan, :status, :user_id)';

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nim' => trim($data['nim'] ?? ''),
            'nama' => trim($data['nama'] ?? ''),
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'tempat_lahir' => trim($data['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => trim($data['alamat'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'no_hp' => trim($data['no_hp'] ?? ''),
            'program_studi_id' => (int)($data['program_studi_id'] ?? 0),
            'angkatan' => $data['angkatan'] ?? null,
            'status' => (int)($data['status'] ?? 1),
            'user_id' => !empty($data['user_id']) ? (int)$data['user_id'] : null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE mahasiswa SET nim = :nim, nama = :nama, jenis_kelamin = :jenis_kelamin, tempat_lahir = :tempat_lahir, tanggal_lahir = :tanggal_lahir, alamat = :alamat, email = :email, no_hp = :no_hp, program_studi_id = :program_studi_id, angkatan = :angkatan, status = :status WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nim' => trim($data['nim'] ?? ''),
            'nama' => trim($data['nama'] ?? ''),
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'tempat_lahir' => trim($data['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => trim($data['alamat'] ?? ''),
            'email' => trim($data['email'] ?? ''),
            'no_hp' => trim($data['no_hp'] ?? ''),
            'program_studi_id' => (int)($data['program_studi_id'] ?? 0),
            'angkatan' => $data['angkatan'] ?? null,
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
