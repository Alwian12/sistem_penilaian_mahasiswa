USE triguna_akademik;

INSERT INTO roles (nama_role, keterangan) VALUES
('admin', 'Administrator sistem akademik'),
('dosen', 'Dosen pengampu mata kuliah'),
('mahasiswa', 'Mahasiswa aktif')
ON DUPLICATE KEY UPDATE keterangan = VALUES(keterangan);

INSERT INTO users (username, email, password, nama, role_id, status) VALUES
('admin', 'admin@triguna.ac.id', '$2y$10$hUtWmKrVF4kBUdq6s/4ogOcoKOBFr6/6YVNeaU0gdZloAywweRQBu', 'Admin Akademik', (SELECT id FROM roles WHERE nama_role = 'admin'), 1),
('dosen1', 'dosen1@triguna.ac.id', '$2y$10$XqgTGwdzJ9BrfQwS2e3nNelv1XlrY3kL0r5tVVQdgCNW1WkV3rM9u', 'Dosen Uji', (SELECT id FROM roles WHERE nama_role = 'dosen'), 1),
('mahasiswa1', 'mahasiswa1@triguna.ac.id', '$2y$10$D660sJ0rQIAkTnsgc9e5A.kZ8hJ1mCIHKqQyQ1Dso02YF5pZ1O8hG', 'Mahasiswa Uji', (SELECT id FROM roles WHERE nama_role = 'mahasiswa'), 1)
ON DUPLICATE KEY UPDATE password = VALUES(password), email = VALUES(email), nama = VALUES(nama), role_id = VALUES(role_id), status = VALUES(status);

INSERT INTO program_studi (kode_prodi, nama_prodi, jenjang, status) VALUES
('SI', 'Sistem Informasi', 'S1', 1),
('TI', 'Teknik Informatika', 'S1', 1)
ON DUPLICATE KEY UPDATE nama_prodi = VALUES(nama_prodi), jenjang = VALUES(jenjang), status = VALUES(status);

INSERT INTO dosen (nidn, nama, email, no_hp, program_studi_id, status, user_id) VALUES
('1234567890', 'Dosen Uji', 'dosen1@triguna.ac.id', '081234567890', (SELECT id FROM program_studi WHERE kode_prodi = 'SI'), 1, (SELECT id FROM users WHERE username = 'dosen1'))
ON DUPLICATE KEY UPDATE nama = VALUES(nama), email = VALUES(email), no_hp = VALUES(no_hp), program_studi_id = VALUES(program_studi_id), status = VALUES(status), user_id = VALUES(user_id);

INSERT INTO mahasiswa (nim, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, email, no_hp, program_studi_id, angkatan, status, user_id) VALUES
('202310001', 'Mahasiswa Uji', 'L', 'Medan', '2002-01-01', 'Jl. Pahlawan', 'mahasiswa1@triguna.ac.id', '081111222333', (SELECT id FROM program_studi WHERE kode_prodi = 'SI'), 2023, 1, (SELECT id FROM users WHERE username = 'mahasiswa1'))
ON DUPLICATE KEY UPDATE nama = VALUES(nama), jenis_kelamin = VALUES(jenis_kelamin), tempat_lahir = VALUES(tempat_lahir), tanggal_lahir = VALUES(tanggal_lahir), alamat = VALUES(alamat), email = VALUES(email), no_hp = VALUES(no_hp), program_studi_id = VALUES(program_studi_id), angkatan = VALUES(angkatan), status = VALUES(status), user_id = VALUES(user_id);

INSERT INTO tahun_akademik (tahun_akademik, semester, status_aktif) VALUES
('2025/2026', 'Ganjil', 1)
ON DUPLICATE KEY UPDATE semester = VALUES(semester), status_aktif = VALUES(status_aktif);

INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, jenis_mk, program_studi_id, status) VALUES
('MK-101', 'Pemrograman Web', 3, 5, 'Wajib', (SELECT id FROM program_studi WHERE kode_prodi = 'SI'), 1)
ON DUPLICATE KEY UPDATE nama_mk = VALUES(nama_mk), sks = VALUES(sks), semester = VALUES(semester), jenis_mk = VALUES(jenis_mk), program_studi_id = VALUES(program_studi_id), status = VALUES(status);

INSERT INTO krs (mahasiswa_id, dosen_id, mata_kuliah_id, tahun_akademik_id, semester, status)
SELECT m.id, d.id, mk.id, ta.id, 5, 1
FROM mahasiswa m
JOIN dosen d ON d.nidn = '1234567890'
JOIN mata_kuliah mk ON mk.kode_mk = 'MK-101'
JOIN tahun_akademik ta ON ta.tahun_akademik = '2025/2026' AND ta.semester = 'Ganjil'
WHERE m.nim = '202310001'
ON DUPLICATE KEY UPDATE dosen_id = VALUES(dosen_id), tahun_akademik_id = VALUES(tahun_akademik_id), semester = VALUES(semester), status = VALUES(status);

INSERT INTO nilai (krs_id, dosen_id, nilai_angka, nilai_huruf, bobot, sks, semester, tahun_akademik_id, status_kelulusan)
SELECT k.id, d.id, 87.5, 'A', 4.00, mk.sks, 5, ta.id, 'LULUS'
FROM krs k
JOIN dosen d ON d.nidn = '1234567890'
JOIN mata_kuliah mk ON mk.id = k.mata_kuliah_id
JOIN tahun_akademik ta ON ta.id = k.tahun_akademik_id
WHERE k.mahasiswa_id = (SELECT id FROM mahasiswa WHERE nim = '202310001')
ON DUPLICATE KEY UPDATE dosen_id = VALUES(dosen_id), nilai_angka = VALUES(nilai_angka), nilai_huruf = VALUES(nilai_huruf), bobot = VALUES(bobot), sks = VALUES(sks), semester = VALUES(semester), tahun_akademik_id = VALUES(tahun_akademik_id), status_kelulusan = VALUES(status_kelulusan);
