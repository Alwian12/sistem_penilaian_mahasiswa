<?php

class TahunAkademikModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM tahun_akademik ORDER BY id DESC';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getActive(): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tahun_akademik WHERE status_aktif = 1 LIMIT 1');
        $stmt->execute();
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO tahun_akademik (tahun_akademik, semester, status_aktif) VALUES (:tahun_akademik, :semester, :status_aktif)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'tahun_akademik' => trim($data['tahun_akademik'] ?? ''),
            'semester' => trim($data['semester'] ?? ''),
            'status_aktif' => (int)($data['status_aktif'] ?? 0),
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE tahun_akademik SET tahun_akademik = :tahun_akademik, semester = :semester, status_aktif = :status_aktif WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'tahun_akademik' => trim($data['tahun_akademik'] ?? ''),
            'semester' => trim($data['semester'] ?? ''),
            'status_aktif' => (int)($data['status_aktif'] ?? 0),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM tahun_akademik WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
