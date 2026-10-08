<?php

class KrsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = 'SELECT k.*, d.nidn, d.nama AS nama_dosen, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, ta.tahun_akademik, ta.semester AS nama_semester
                FROM krs k
                LEFT JOIN mahasiswa m ON m.id = k.mahasiswa_id
            LEFT JOIN dosen d ON d.id = k.dosen_id
                LEFT JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                LEFT JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                ORDER BY k.id DESC';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getByMahasiswaId(int $mahasiswaId): array
    {
        $sql = 'SELECT k.*, mk.kode_mk, mk.nama_mk, mk.sks, ta.tahun_akademik
                FROM krs k
                LEFT JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                LEFT JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE k.mahasiswa_id = :mahasiswa_id
                ORDER BY ta.tahun_akademik DESC, k.semester ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);
        return $stmt->fetchAll();
    }

    public function getByDosenId(int $dosenId): array
    {
        $sql = 'SELECT k.*, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, mk.sks,
                       ta.tahun_akademik, ta.semester AS nama_semester
                FROM krs k
                JOIN mahasiswa m ON m.id = k.mahasiswa_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE k.dosen_id = :dosen_id
                ORDER BY ta.tahun_akademik DESC, k.semester ASC, m.nama ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dosen_id' => $dosenId]);
        return $stmt->fetchAll();
    }

    public function getByDosenScope(int $dosenId, int $programStudiId): array
    {
        $sql = 'SELECT k.*, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, mk.sks,
                       ta.tahun_akademik, ta.semester AS nama_semester
                FROM krs k
                JOIN mahasiswa m ON m.id = k.mahasiswa_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE (k.dosen_id = :dosen_id OR m.program_studi_id = :program_studi_id)
                ORDER BY ta.tahun_akademik DESC, k.semester ASC, m.nama ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dosen_id' => $dosenId, 'program_studi_id' => $programStudiId]);
        return $stmt->fetchAll();
    }

    public function findByMahasiswaMatkulSemester(int $mahasiswaId, int $matkulId, int $semester, int $tahunAkademikId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM krs WHERE mahasiswa_id = :mahasiswa_id AND mata_kuliah_id = :mata_kuliah_id AND semester = :semester AND tahun_akademik_id = :tahun_akademik_id LIMIT 1');
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'mata_kuliah_id' => $matkulId,
            'semester' => $semester,
            'tahun_akademik_id' => $tahunAkademikId,
        ]);
        return $stmt->fetch() ?: null;
    }

    public function belongsToDosen(int $krsId, int $dosenId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM krs WHERE id = :krs_id AND dosen_id = :dosen_id');
        $stmt->execute(['krs_id' => $krsId, 'dosen_id' => $dosenId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function belongsToDosenScope(int $krsId, int $dosenId, int $programStudiId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM krs k JOIN mahasiswa m ON m.id = k.mahasiswa_id WHERE k.id = :krs_id AND (k.dosen_id = :dosen_id OR m.program_studi_id = :program_studi_id)');
        $stmt->execute(['krs_id' => $krsId, 'dosen_id' => $dosenId, 'program_studi_id' => $programStudiId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO krs (mahasiswa_id, dosen_id, mata_kuliah_id, tahun_akademik_id, semester, status)
            VALUES (:mahasiswa_id, :dosen_id, :mata_kuliah_id, :tahun_akademik_id, :semester, :status)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'mahasiswa_id' => (int)($data['mahasiswa_id'] ?? 0),
            'dosen_id' => !empty($data['dosen_id']) ? (int)$data['dosen_id'] : null,
            'mata_kuliah_id' => (int)($data['mata_kuliah_id'] ?? 0),
            'tahun_akademik_id' => (int)($data['tahun_akademik_id'] ?? 0),
            'semester' => (int)($data['semester'] ?? 0),
            'status' => (int)($data['status'] ?? 1),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM krs WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
