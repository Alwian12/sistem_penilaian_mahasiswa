CREATE DATABASE IF NOT EXISTS triguna_akademik CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE triguna_akademik;

CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_role VARCHAR(30) NOT NULL UNIQUE,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(150) DEFAULT NULL,
    role_id INT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE program_studi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_prodi VARCHAR(30) NOT NULL UNIQUE,
    nama_prodi VARCHAR(150) NOT NULL,
    jenjang VARCHAR(30) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nim VARCHAR(30) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('L','P') DEFAULT NULL,
    tempat_lahir VARCHAR(100) DEFAULT NULL,
    tanggal_lahir DATE DEFAULT NULL,
    alamat TEXT,
    email VARCHAR(150) DEFAULT NULL,
    no_hp VARCHAR(30) DEFAULT NULL,
    program_studi_id INT NOT NULL,
    angkatan YEAR DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    user_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_studi_id) REFERENCES program_studi(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE dosen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nidn VARCHAR(30) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    no_hp VARCHAR(30) DEFAULT NULL,
    program_studi_id INT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    user_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_studi_id) REFERENCES program_studi(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE mata_kuliah (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_mk VARCHAR(30) NOT NULL UNIQUE,
    nama_mk VARCHAR(150) NOT NULL,
    sks INT NOT NULL,
    semester INT NOT NULL,
    jenis_mk VARCHAR(50) DEFAULT NULL,
    program_studi_id INT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_studi_id) REFERENCES program_studi(id)
);

CREATE TABLE tahun_akademik (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tahun_akademik VARCHAR(20) NOT NULL,
    semester VARCHAR(20) NOT NULL,
    status_aktif TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE krs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mahasiswa_id INT NOT NULL,
    dosen_id INT DEFAULT NULL,
    mata_kuliah_id INT NOT NULL,
    tahun_akademik_id INT NOT NULL,
    semester INT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id),
    FOREIGN KEY (dosen_id) REFERENCES dosen(id),
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id),
    FOREIGN KEY (tahun_akademik_id) REFERENCES tahun_akademik(id),
    UNIQUE (mahasiswa_id, mata_kuliah_id, semester, tahun_akademik_id)
);

CREATE TABLE nilai_huruf (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nilai_huruf VARCHAR(10) NOT NULL,
    bobot DECIMAL(4,2) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE nilai (
    id INT PRIMARY KEY AUTO_INCREMENT,
    krs_id INT NOT NULL,
    dosen_id INT DEFAULT NULL,
    nilai_angka DECIMAL(4,2) DEFAULT NULL,
    nilai_huruf VARCHAR(10) DEFAULT NULL,
    bobot DECIMAL(4,2) DEFAULT NULL,
    sks INT NOT NULL,
    semester INT NOT NULL,
    tahun_akademik_id INT NOT NULL,
    status_kelulusan ENUM('LULUS','TIDAK LULUS') DEFAULT 'LULUS',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (krs_id) REFERENCES krs(id),
    FOREIGN KEY (dosen_id) REFERENCES dosen(id),
    FOREIGN KEY (tahun_akademik_id) REFERENCES tahun_akademik(id)
);

CREATE TABLE log_activity (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    aktivitas VARCHAR(150) NOT NULL,
    modul VARCHAR(100) DEFAULT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) DEFAULT NULL,
    data_terkait TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX idx_users_role_id ON users(role_id);
CREATE INDEX idx_mahasiswa_program_studi ON mahasiswa(program_studi_id);
CREATE INDEX idx_dosen_program_studi ON dosen(program_studi_id);
CREATE INDEX idx_mata_kuliah_program_studi ON mata_kuliah(program_studi_id);
CREATE INDEX idx_krs_mahasiswa ON krs(mahasiswa_id);
CREATE INDEX idx_nilai_krs ON nilai(krs_id);
CREATE INDEX idx_nilai_tahun_akademik ON nilai(tahun_akademik_id);

INSERT INTO roles (nama_role, keterangan) VALUES
('admin', 'Administrator sistem akademik'),
('dosen', 'Dosen pengampu mata kuliah'),
('mahasiswa', 'Mahasiswa aktif');

INSERT INTO program_studi (kode_prodi, nama_prodi, jenjang, status) VALUES
('SI', 'Sistem Informasi', 'S1', 1),
('TI', 'Teknik Informatika', 'S1', 1);

INSERT INTO users (username, email, password, nama, role_id, status) VALUES
('admin', 'admin@triguna.ac.id', '$2y$10$YDf9Sm4c7aj8Ai6e6/s5Qe8iG2nWVVt5eA3Yl4e2V0r0Bf2uBEqC2', 'Admin Akademik', 1, 1);

INSERT INTO nilai_huruf (kode, nilai_huruf, bobot, status) VALUES
('A', 'A', 4.00, 1),
('A-', 'A-', 3.75, 1),
('B+', 'B+', 3.50, 1),
('B', 'B', 3.00, 1),
('B-', 'B-', 2.75, 1),
('C+', 'C+', 2.50, 1),
('C', 'C', 2.00, 1),
('C-', 'C-', 1.75, 1),
('D', 'D', 1.00, 1),
('E', 'E', 0.00, 1);
