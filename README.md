# SISTEM INFORMASI REKAP NILAI TRANSKRIP DAN IPK MAHASISWA

Dokumen ini berisi rancangan awal sistem akademik berbasis web yang akan dikembangkan secara bertahap untuk STMIK Triguna Dharma.

## Status

Tahap saat ini: Analisis kebutuhan, aktor, arsitektur, flowchart, use case, ERD awal, dan struktur proyek.

## Daftar Dokumen

- [docs/01-analisis-kebutuhan.md](docs/01-analisis-kebutuhan.md)
- [docs/02-flowchart.md](docs/02-flowchart.md)
- [docs/03-use-case-erd.md](docs/03-use-case-erd.md)
- [docs/04-struktur-ui.md](docs/04-struktur-ui.md)

## Ringkasan

Sistem ini fokus pada pengelolaan nilai mahasiswa, rekap nilai, transkrip akademik, IPS, dan IPK. Aplikasi dibangun dengan pendekatan modular agar dapat berkembang menjadi sistem akademik yang lebih besar di masa depan.

## Prinsip utama

- Keamanan: password hashing, sesi, RBAC, prepared statement, validasi.
- Akurasi: semua data nilai dihitung ulang secara otomatis, bukan disimpan sebagai nilai final permanen.
- Skalabilitas: struktur folder dan database dirancang agar bisa diperluas ke KRS online, KHS, jadwal, presensi, dan modul akademik lain.

## Tahapan proyek yang akan dilakukan

1. Analisis kebutuhan dan aktor.
2. Flowchart dan use case.
3. ERD dan desain database.
4. Struktur navigasi dan UI/UX.
5. Setup project dan database.
6. Authentication dan role management.
7. Master data, KRS, dan nilai.
8. IPS, IPK, transkrip, dashboard, dan laporan.
9. Testing, keamanan, optimasi, dan dokumentasi pengembangan lanjut.

---

Dokumen ini merupakan output tahap pertama sesuai dengan requirement proyek. Setelah rancangan ini disetujui, tahapan berikutnya akan dimulai dengan setup database dan struktur project.
