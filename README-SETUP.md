# Panduan Setup Database Laragon

## Langkah 1: Jalankan MySQL di Laragon
- Buka Laragon
- Klik Start All atau start MySQL
- Pastikan MySQL berjalan pada port 3306

## Langkah 2: Import database
Buka terminal MySQL atau phpMyAdmin di Laragon.
Jalankan script berikut:

```sql
SOURCE C:/laragon/www/dinda/database/setup.sql;
```

Atau jalankan file SQL melalui phpMyAdmin.

Jika database sudah pernah dibuat sebelum relasi dosen pada KRS ditambahkan, jalankan juga:

```sql
SOURCE C:/laragon/www/dinda/database/migration_assign_dosen_to_krs.sql;
```

Relasi ini menghubungkan akun dosen dengan prodi dan KRS mahasiswa. Dosen kemudian hanya melihat dan memberi nilai pada mahasiswa dalam cakupan prodinya atau KRS yang ditugaskan.

## Langkah 3: Cek aplikasi
Akses URL:

```text
http://localhost/dinda/
```

Akun default yang tersedia:
- Username: admin
- Password: admin123

## Catatan
- Password tersimpan dalam bentuk hash.
- Semua file login dan dashboard dasar sudah dibuat.
- Setelah database aktif, sistem akan berfungsi sesuai flow login berdasarkan role.
