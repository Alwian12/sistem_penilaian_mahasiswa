# 4. Struktur Navigasi, UI/UX, dan Proyek

## 4.1 Struktur navigasi lengkap

### Public
- Login
- Lupa password (opsional di tahap lanjutan)

### Admin
- Dashboard
- Mahasiswa
- Dosen
- Program Studi
- Mata Kuliah
- Tahun Akademik
- Semester
- KRS
- Nilai
- Transkrip
- Laporan
- Pengguna
- Role
- Pengaturan
- Audit Log

### Dosen
- Dashboard
- Mata Kuliah
- Mahasiswa
- Input Nilai
- Rekap Nilai
- Statistik Nilai

### Mahasiswa
- Dashboard
- Profil
- KRS
- Nilai
- IPS
- IPK
- Transkrip
- Riwayat Akademik

## 4.2 Rancangan UI/UX

### Desain visual
- Background utama berwarna putih krem atau abu muda agar terlihat bersih dan profesional.
- Aksen warna utama: biru navy, abu gelap, dan putih.
- Warna status dapat diberikan pada badge seperti lulus, aktif, belum diinput, valid.
- Gunakan card untuk dashboard statistik, ringkasan akademik, dan data penting.
- Tabel dibangun dengan design yang rapi, hover, sorting, dan filter.
- Form memakai spacing konsisten dan label yang jelas.

### Prinsip UX
- Prioritaskan akses cepat ke data penting.
- Pastikan setiap halaman memiliki breadcrumb atau navigasi yang jelas.
- Gunakan notifikasi toast untuk aksi sukses atau error.
- Gunakan modal konfirmasi untuk delete data.
- Pastikan semua form accessible dengan label, ukuran input konsisten, dan tombol jelas.

### Fitur interaksi
- pencarian cepat berdasarkan NIM, nama, prodi, semester, tahun akademik
- filter data pada tabel
- dropdown role untuk akses
- toggle status aktif/nonaktif
- loading spinner saat proses AJAX atau validasi

## 4.3 Struktur folder proyek (modular)

```text
project/
├── config/
│   └── database.php
├── controllers/
│   ├── AuthController.php
│   ├── AdminController.php
│   ├── DosenController.php
│   └── MahasiswaController.php
├── models/
│   ├── UserModel.php
│   ├── MahasiswaModel.php
│   ├── DosenModel.php
│   ├── MataKuliahModel.php
│   ├── NilaiModel.php
│   └── ReportModel.php
├── views/
│   ├── auth/
│   ├── admin/
│   ├── dosen/
│   ├── mahasiswa/
│   └── layouts/
├── public/
│   ├── index.php
│   └── assets/
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── vendor/
├── routes/
│   └── web.php
├── database/
│   ├── schema.sql
│   └── seeders/
├── migrations/
│   └── 001_create_users.php
├── helpers/
│   ├── auth.php
│   ├── format.php
│   └── calculator.php
├── middleware/
│   ├── AuthMiddleware.php
│   └── RoleMiddleware.php
├── storage/
│   ├── logs/
│   └── uploads/
└── README.md
```

## 4.4 Wireframe halaman utama

### Halaman login
- bidang email/username
- password
- tombol masuk
- branding institusi
- ilustrasi sederhana atau logo kampus

### Dashboard admin
- header navbar
- sidebar
- card statistik
- grafik IPK dan distribusi mahasiswa
- tabel ringkasan nilai

### Dashboard dosen
- mata kuliah yang diampu
- jumlah mahasiswa
- nilai belum diinput
- rekap kelas

### Dashboard mahasiswa
- card IPK, IPS terakhir, total SKS, jumlah matakuliah
- grafik perkembangan IPS
- ringkasan nilai terbaru

### Halaman transkrip
- header institusi
- identitas mahasiswa
- tabel daftar matakuliah
- footer tanda tangan dan nomor transkrip
- layout print-friendly

## 4.5 Rencana pengembangan berikutnya
Setelah modul utama selesai, sistem dapat dikembangkan dengan tambahan:
- KRS online
- KHS
- jadwal kuliah
- presensi
- pembayaran UKT
- portal dosen
- portal orang tua
- notifikasi
- QR code transkrip
- import data Excel
- API untuk integrasi lainnya

## 4.6 Checklist target akhir
- sistem aman dan modular
- mudah dikembangkan
- user experience modern
- perhitungan IPS/IPK otomatis berdasarkan data nyata
- transkrip diproduksi dari data nilai yang terintegrasi
- data master dan log aktivitas terstruktur dengan relasi yang jelas

Dokumen ini adalah rancangan awal yang akan menjadi dasar implementasi berikutnya.
