# 1. Gambaran Umum Sistem

## 1.1 Nama sistem
SISTEM INFORMASI REKAP NILAI TRANSKRIP DAN IPK MAHASISWA BERBASIS WEB DI STMIK TRIGUNA DHARMA

## 1.2 Latar belakang
STMIK Triguna Dharma membutuhkan sistem akademik digital yang cepat, aman, dan terstruktur untuk mengelola data mahasiswa, nilai, rekap akademik, IPS, dan IPK. Saat ini proses pengolahan nilai belum terintegrasi dengan baik, sehingga sering terjadi duplikasi data, kesalahan perhitungan, dan proses rekap yang berulang.

Sistem ini dirancang dengan fokus utama pada:
- pengelolaan data mahasiswa
- pengelolaan nilai
- rekap transkrip
- perhitungan IPS dan IPK
- dashboard informasi akademik
- keamanan akses berbasis role
- struktur modular yang bisa dikembangkan di tahap berikutnya

## 1.3 Tujuan sistem
Tujuan utama sistem adalah:
- membantu admin mengelola data akademik secara digital
- memudahkan dosen dalam input dan revisi nilai
- memudahkan mahasiswa melihat profil, nilai, IPS, IPK, dan transkrip
- memastikan perhitungan IPS dan IPK otomatis dan akurat
- menampilkan data akademik secara terstruktur dan modern
- menyediakan fondasi yang bisa berkembang ke sistem akademik yang lebih besar

## 1.4 Permasalahan yang diselesaikan
- data mahasiswa dan nilai masih tersebar
- proses rekap nilai masih manual dan rawan kesalahan
- perhitungan IPS/IPK belum otomatis
- tidak ada kontrol akses yang jelas untuk masing-masing role
- transkrip sulit dibuat dalam format yang rapi dan dapat dicetak
- sulit melakukan audit dan monitoring aktivitas admin/dosen
- struktur data belum siap dikembangkan ke modul akademik berikutnya

## 1.5 Aktor/user
### 1) Admin / Operator
Mengelola seluruh data dan sistem.

### 2) Dosen
Mengelola nilai dan data akademik yang menjadi tanggung jawabnya.

### 3) Mahasiswa
Melihat data akademik dirinya sendiri.

## 1.6 Hak akses masing-masing user

### Admin / Operator
- login/logout
- dashboard umum
- CRUD mahasiswa, dosen, prodi, matakuliah, tahun akademik, semester
- mengelola KRS
- menginput, mengubah, dan mengoreksi nilai
- melihat seluruh data nilai dan statistik akademik
- melihat IPS dan IPK mahasiswa
- melihat transkrip mahasiswa
- membuat laporan dan export
- mengelola akun pengguna dan role
- mengatur konfigurasi sistem dasar

### Dosen
- login/logout
- dashboard dosen
- melihat mata kuliah yang diampu
- melihat daftar mahasiswa pada mata kuliah
- menginput nilai
- mengedit nilai sebelum dikunci
- melihat rekap nilai kelas
- melihat statistik nilai mata kuliah
- tidak dapat mengubah data master yang bukan tanggung jawabnya

### Mahasiswa
- login/logout
- dashboard mahasiswa
- melihat profil
- melihat KRS
- melihat nilai per semester
- melihat IPS dan IPK
- melihat transkrip
- melihat riwayat akademik
- hanya mengakses data dirinya sendiri

## 1.7 Daftar fitur utama
### Modul Autentikasi
- login
- logout
- session management
- password hashing
- role-based access control
- redirect berdasarkan role

### Modul Dashboard
- dashboard admin
- dashboard dosen
- dashboard mahasiswa
- statistik akademik
- grafik perkembangan IPS/IPK

### Modul Master Data
- mahasiswa
- dosen
- program studi
- mata kuliah
- tahun akademik
- semester

### Modul KRS
- daftar KRS mahasiswa
- validasi duplikasi mata kuliah
- menghitung total SKS
- menghubungkan KRS dengan nilai

### Modul Nilai
- input nilai mahasiswa
- konversi huruf dan bobot
- validasi data
- koreksi nilai

### Modul Perhitungan
- IPS per semester
- IPK kumulatif
- semua proses dihitung ulang dari data nilai

### Modul Transkrip
- tampilan transkrip profesional
- layout cetak dengan CSS print
- informasi mata kuliah, SKS, nilai, bobot, mutu

### Modul Laporan
- rekap nilai
- laporan akademik
- export print/pdf/excel

### Modul Keamanan dan Audit
- session protection
- RBAC
- CSRF
- sanitasi output
- logging aktivitas

## 1.8 Alur utama sistem
1. Pengguna login ke sistem.
2. Sistem mengecek role pengguna.
3. Pengguna diarahkan ke dashboard yang sesuai.
4. Admin mengelola data master dan KRS.
5. Dosen menginput atau memperbarui nilai mata kuliah.
6. Sistem menghitung nilai, bobot, IPS, dan IPK secara otomatis.
7. Mahasiswa melihat data akademiknya sendiri.
8. Admin atau mahasiswa mencetak transkrip atau laporan.
9. Sistem mencatat aktivitas pengguna untuk audit.

## 1.9 Struktur navigasi
### Public
- Login

### Admin
- Dashboard
- Mahasiswa
- Dosen
- Program Studi
- Mata Kuliah
- Tahun Akademik
- KRS
- Nilai
- Transkrip
- Laporan
- Pengguna
- Pengaturan

### Dosen
- Dashboard
- Mata Kuliah
- Mahasiswa
- Input Nilai
- Rekap Nilai

### Mahasiswa
- Dashboard
- Profil
- KRS
- Nilai
- IPS
- IPK
- Transkrip
- Riwayat Akademik

## 1.10 Rancangan UI/UX
Desain yang akan digunakan:
- minimalis dan modern
- warna netral dengan aksen profesional
- sidebar modern dengan navigasi jelas
- card statistik untuk dashboard
- table rapi dan responsif
- form dengan validasi jelas
- notifikasi toast
- modal konfirmasi saat delete
- empty state, loading state, dan error state
- mobile friendly dan desktop friendly
- fokus pada kemudahan membaca data akademik

## 1.11 Struktur folder project

```text
/config
/controllers
/models
/views
/public
/assets
/css
/js
/images
/routes
/database
/migrations
/helpers
/middleware
/storage
```

## 1.12 Rencana tahapan coding
1. Setup project
2. Database
3. Authentication
4. Role management
5. Master data
6. KRS
7. Nilai
8. IPS
9. IPK
10. Transkrip
11. Dashboard
12. Laporan
13. UI polishing
14. Testing

Setiap tahap harus menghasilkan sistem yang dapat dijalankan sebelum melanjutkan ke tahap berikutnya.
