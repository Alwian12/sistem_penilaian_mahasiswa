USE triguna_akademik;

INSERT INTO program_studi (kode_prodi, nama_prodi, jenjang, status) VALUES
('MK', 'Manajemen Informatika', 'D3', 1),
('DKV', 'Desain Komunikasi Visual', 'D4', 1);

INSERT INTO users (username, email, password, nama, role_id, status) VALUES
('dosen1', 'dosen1@triguna.ac.id', '$2y$10$5Zt2w.IPi9qfTkD3c2LPDuZddjIemlXgt2bGS6ULbpl8e0RuT8V1W', 'Dosen A', 2, 1),
('mhs1', 'mhs1@triguna.ac.id', '$2y$10$5Zt2w.IPi9qfTkD3c2LPDuZddjIemlXgt2bGS6ULbpl8e0RuT8V1W', 'Mahasiswa A', 3, 1);

INSERT INTO mahasiswa (nim, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, email, no_hp, program_studi_id, angkatan, status, user_id) VALUES
('2023001', 'Mahasiswa A', 'L', 'Medan', '2002-05-10', 'Jl. Merdeka No. 1', 'mhs1@triguna.ac.id', '081234567890', 1, 2023, 1, 3);

INSERT INTO dosen (nidn, nama, email, no_hp, program_studi_id, status, user_id) VALUES
('110001', 'Dosen A', 'dosen1@triguna.ac.id', '081111111111', 1, 1, 2);

INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, jenis_mk, program_studi_id, status) VALUES
('MK101', 'Pemrograman Web', 3, 1, 'Wajib', 1, 1),
('MK102', 'Basis Data', 3, 1, 'Wajib', 1, 1),
('MK201', 'Algoritma dan Struktur Data', 3, 2, 'Wajib', 1, 1);

INSERT INTO tahun_akademik (tahun_akademik, semester, status_aktif) VALUES
('2023/2024', 'Ganjil', 0),
('2023/2024', 'Genap', 1);
