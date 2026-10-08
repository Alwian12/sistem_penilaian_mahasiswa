<?php

class NilaiModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getNilaiHurufList(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM nilai_huruf WHERE status = 1 ORDER BY bobot DESC, kode DESC');
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $sql = 'SELECT n.*, k.semester AS krs_semester, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, mk.sks,
                       ta.tahun_akademik, d.nama AS nama_dosen
                FROM nilai n
                LEFT JOIN krs k ON k.id = n.krs_id
                LEFT JOIN mahasiswa m ON m.id = k.mahasiswa_id
                LEFT JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                LEFT JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                LEFT JOIN dosen d ON d.id = n.dosen_id
                ORDER BY m.nama ASC, ta.tahun_akademik DESC, k.semester ASC';

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getByKrsId(int $krsId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM nilai WHERE krs_id = :krs_id LIMIT 1');
        $stmt->execute(['krs_id' => $krsId]);
        return $stmt->fetch() ?: null;
    }

    public function upsertFromKrs(array $data): bool
    {
        $krsId = (int)($data['krs_id'] ?? 0);
        $dosenId = !empty($data['dosen_id']) ? (int)$data['dosen_id'] : null;
        $nilaiAngka = (float)($data['nilai_angka'] ?? 0);

        $normalized = $this->convertNilaiAngka($nilaiAngka);
        if ($normalized === null) {
            return false;
        }

        $krs = $this->getKrsDetailById($krsId);
        if (!$krs) {
            return false;
        }

        $hasExisting = $this->getByKrsId($krsId);
        $sql = $hasExisting
            ? 'UPDATE nilai SET dosen_id = :dosen_id, nilai_angka = :nilai_angka, nilai_huruf = :nilai_huruf, bobot = :bobot, sks = :sks, semester = :semester, tahun_akademik_id = :tahun_akademik_id, status_kelulusan = :status_kelulusan WHERE krs_id = :krs_id'
            : 'INSERT INTO nilai (krs_id, dosen_id, nilai_angka, nilai_huruf, bobot, sks, semester, tahun_akademik_id, status_kelulusan)
               VALUES (:krs_id, :dosen_id, :nilai_angka, :nilai_huruf, :bobot, :sks, :semester, :tahun_akademik_id, :status_kelulusan)';

        $stmt = $this->pdo->prepare($sql);
        $params = [
            'krs_id' => $krsId,
            'dosen_id' => $dosenId,
            'nilai_angka' => $nilaiAngka,
            'nilai_huruf' => $normalized['nilai_huruf'],
            'bobot' => $normalized['bobot'],
            'sks' => (int)($krs['sks'] ?? 0),
            'semester' => (int)($krs['semester'] ?? 0),
            'tahun_akademik_id' => (int)($krs['tahun_akademik_id'] ?? 0),
            'status_kelulusan' => $nilaiAngka >= 60 ? 'LULUS' : 'TIDAK LULUS',
        ];

        return $stmt->execute($params);
    }

    public function getIpsPerSemester(int $mahasiswaId): array
    {
        $sql = 'SELECT k.semester, ta.tahun_akademik, SUM(n.bobot * mk.sks) AS total_mutu, SUM(mk.sks) AS total_sks
                FROM nilai n
                JOIN krs k ON k.id = n.krs_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE k.mahasiswa_id = :mahasiswa_id
                GROUP BY k.semester, ta.tahun_akademik
                ORDER BY ta.tahun_akademik ASC, k.semester ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['total_mutu'] = (float)($row['total_mutu'] ?? 0);
            $row['total_sks'] = (float)($row['total_sks'] ?? 0);
            $row['ips'] = $row['total_sks'] > 0 ? round($row['total_mutu'] / $row['total_sks'], 2) : 0.0;
        }

        return $rows;
    }

    public function getIpk(int $mahasiswaId): float
    {
        $sql = 'SELECT SUM(n.bobot * mk.sks) AS total_mutu, SUM(mk.sks) AS total_sks
                FROM nilai n
                JOIN krs k ON k.id = n.krs_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                WHERE k.mahasiswa_id = :mahasiswa_id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);
        $row = $stmt->fetch();

        $totalMutu = (float)($row['total_mutu'] ?? 0);
        $totalSks = (float)($row['total_sks'] ?? 0);

        return $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0.0;
    }

    public function getTranskripByMahasiswaId(int $mahasiswaId): array
    {
        $sql = 'SELECT n.*, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, mk.sks,
                       ta.tahun_akademik, k.semester
                FROM nilai n
                JOIN krs k ON k.id = n.krs_id
                JOIN mahasiswa m ON m.id = k.mahasiswa_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE k.mahasiswa_id = :mahasiswa_id
                ORDER BY ta.tahun_akademik ASC, k.semester ASC, mk.nama_mk ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);
        return $stmt->fetchAll();
    }

    public function getByDosenId(int $dosenId): array
    {
        $sql = 'SELECT n.*, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, ta.tahun_akademik, k.semester
                FROM nilai n
                JOIN krs k ON k.id = n.krs_id
                JOIN mahasiswa m ON m.id = k.mahasiswa_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE n.dosen_id = :dosen_id
                ORDER BY ta.tahun_akademik DESC, k.semester ASC, m.nama ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dosen_id' => $dosenId]);
        return $stmt->fetchAll();
    }

    public function getByDosenScope(int $dosenId, int $programStudiId): array
    {
        $sql = 'SELECT n.*, m.nim, m.nama AS nama_mahasiswa, mk.kode_mk, mk.nama_mk, ta.tahun_akademik, k.semester
                FROM nilai n
                JOIN krs k ON k.id = n.krs_id
                JOIN mahasiswa m ON m.id = k.mahasiswa_id
                JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
                JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
                WHERE (n.dosen_id = :dosen_id OR (m.program_studi_id = :program_studi_id AND k.dosen_id IS NULL))
                ORDER BY ta.tahun_akademik DESC, k.semester ASC, m.nama ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dosen_id' => $dosenId, 'program_studi_id' => $programStudiId]);
        return $stmt->fetchAll();
    }

    public function convertNilaiAngka(float $nilai): ?array
    {
        if ($nilai < 0 || $nilai > 100) {
            return null;
        }

        if ($nilai >= 85) {
            return ['nilai_huruf' => 'A', 'bobot' => 4.00];
        }

        if ($nilai >= 80) {
            return ['nilai_huruf' => 'A-', 'bobot' => 3.75];
        }

        if ($nilai >= 75) {
            return ['nilai_huruf' => 'B+', 'bobot' => 3.50];
        }

        if ($nilai >= 70) {
            return ['nilai_huruf' => 'B', 'bobot' => 3.00];
        }

        if ($nilai >= 65) {
            return ['nilai_huruf' => 'B-', 'bobot' => 2.75];
        }

        if ($nilai >= 60) {
            return ['nilai_huruf' => 'C+', 'bobot' => 2.50];
        }

        if ($nilai >= 55) {
            return ['nilai_huruf' => 'C', 'bobot' => 2.00];
        }

        if ($nilai >= 50) {
            return ['nilai_huruf' => 'C-', 'bobot' => 1.75];
        }

        if ($nilai >= 40) {
            return ['nilai_huruf' => 'D', 'bobot' => 1.00];
        }

        return ['nilai_huruf' => 'E', 'bobot' => 0.00];
    }

    private function getKrsDetailById(int $krsId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT k.*, mk.sks FROM krs k LEFT JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id WHERE k.id = :id LIMIT 1');
        $stmt->execute(['id' => $krsId]);
        return $stmt->fetch() ?: null;
    }
}
