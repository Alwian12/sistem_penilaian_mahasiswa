# 2. Flowchart Sistem

## 2.1 Flowchart keseluruhan sistem

```mermaid
flowchart TD
    A[Mulai] --> B[Login ke sistem]
    B --> C{Validasi user?}
    C -- Tidak --> D[Tampilkan error login]
    D --> B
    C -- Ya --> E{Role user?}
    E -- Admin --> F[Dashboard Admin]
    E -- Dosen --> G[Dashboard Dosen]
    E -- Mahasiswa --> H[Dashboard Mahasiswa]

    F --> F1[Kelola master data]
    F --> F2[Kelola KRS]
    F --> F3[Kelola nilai]
    F --> F4[Hitung IPS/IPK]
    F --> F5[Buat transkrip]
    F --> F6[Buat laporan]

    G --> G1[Lihat mata kuliah]
    G --> G2[Lihat mahasiswa]
    G --> G3[Input nilai]
    G --> G4[Rekap nilai]

    H --> H1[Lihat profil]
    H --> H2[Lihat KRS]
    H --> H3[Lihat nilai]
    H --> H4[Lihat IPS/IPK]
    H --> H5[Cetak transkrip]

    F1 --> F7[Log aktivitas]
    F2 --> F7
    F3 --> F7
    F4 --> F7
    F5 --> F7
    F6 --> F7
    G1 --> F7
    G3 --> F7
    H1 --> F7
    H5 --> F7

    F7 --> Z[Selesai]
```

## 2.2 Flowchart login

```mermaid
flowchart TD
    A[Mulai] --> B[Buka halaman login]
    B --> C[Input username/email dan password]
    C --> D[Validasi input]
    D --> E{Data valid?}
    E -- Tidak --> F[Tampilkan pesan error]
    F --> B
    E -- Ya --> G[Cek user di database]
    G --> H{User ditemukan?}
    H -- Tidak --> F
    H -- Ya --> I[Cek password hash]
    I --> J{Password cocok?}
    J -- Tidak --> F
    J -- Ya --> K[Session login dibuat]
    K --> L{Role user?}
    L -- Admin --> M[Redirect dashboard admin]
    L -- Dosen --> N[Redirect dashboard dosen]
    L -- Mahasiswa --> O[Redirect dashboard mahasiswa]
    M --> P[Selesai]
    N --> P
    O --> P
```

## 2.3 Flowchart admin

```mermaid
flowchart TD
    A[Admin login] --> B[Dashboard admin]
    B --> C{Menu yang dipilih}
    C -- Master data --> D[CRUD mahasiswa/prodi/dosen/matkul/tahun akademik]
    C -- KRS --> E[Kelola KRS mahasiswa]
    C -- Nilai --> F[Input/ubah/koreksi nilai]
    C -- IPS/IPK --> G[Hitung dan tampilkan IPS/IPK]
    C -- Transkrip --> H[Buat dan cetak transkrip]
    C -- Laporan --> I[Generate laporan]
    C -- Pengguna --> J[Kelola akun & role]
    C -- Statistik --> K[Lihat grafik dan statistik]

    D --> L[Simpan ke database]
    E --> L
    F --> L
    G --> L
    H --> L
    I --> L
    J --> L
    K --> L
    L --> M[Log aktivitas]
    M --> N[Selesai]
```

## 2.4 Flowchart dosen

```mermaid
flowchart TD
    A[Dosen login] --> B[Dashboard dosen]
    B --> C[Lihat mata kuliah yang diampu]
    C --> D[Lihat daftar mahasiswa]
    D --> E{Input nilai?}
    E -- Ya --> F[Masukkan nilai mahasiswa]
    F --> G[Validasi nilai]
    G --> H{Nilai sudah dikunci?}
    H -- Tidak --> I[Simpan nilai]
    H -- Ya --> J[Tolak perubahan]
    I --> K[Rekap nilai kelas]
    J --> K
    K --> L[Log aktivitas]
    L --> M[Selesai]
```

## 2.5 Flowchart mahasiswa

```mermaid
flowchart TD
    A[Mahasiswa login] --> B[Dashboard mahasiswa]
    B --> C[Lihat profil]
    B --> D[Lihat KRS]
    B --> E[Lihat nilai per semester]
    B --> F[Lihat IPS]
    B --> G[Lihat IPK]
    B --> H[Lihat transkrip]
    B --> I[Lihat riwayat akademik]
    C --> J[Tampilkan data pribadi]
    D --> K[Tampilkan KRS milik sendiri]
    E --> L[Tampilkan rekap nilai]
    F --> M[Hitung IPS otomatis]
    G --> N[Hitung IPK otomatis]
    H --> O[Cetak atau unduh transkrip]
    I --> P[Tampilkan grafik perkembangan]
    J --> Q[Selesai]
    K --> Q
    L --> Q
    M --> Q
    N --> Q
    O --> Q
    P --> Q
```

## 2.6 Flowchart input nilai

```mermaid
flowchart TD
    A[Mulai input nilai] --> B[Admin/Dosen memilih KRS mahasiswa]
    B --> C[Cek mata kuliah valid]
    C --> D{Ada di KRS?}
    D -- Tidak --> E[Tolak input]
    E --> Z[Selesai]
    D -- Ya --> F[Masukkan nilai angka & huruf]
    F --> G[Cek konversi bobot dan SKS]
    G --> H{Nilai valid?}
    H -- Tidak --> I[Tampilkan error validasi]
    I --> B
    H -- Ya --> J[Simpan data nilai]
    J --> K[Hitung mutu]
    K --> L[Update rekam akademik]
    L --> M[Log aktivitas]
    M --> N[Selesai]
```

## 2.7 Flowchart perhitungan IPS

```mermaid
flowchart TD
    A[Mulai hitung IPS] --> B[Pilih semester tertentu]
    B --> C[Ambil mata kuliah dan nilai mahasiswa]
    C --> D[Filter data sesuai semester]
    D --> E[Ambil bobot nilai dari tabel konfigurasi]
    E --> F[Hitung total mutu = Σ(SK S × bobot)]
    F --> G[Hitung total SKS]
    G --> H{Total SKS > 0?}
    H -- Tidak --> I[IPS = 0]
    H -- Ya --> J[IPS = total mutu / total SKS]
    I --> K[Simpan/hasil tampilkan]
    J --> K
    K --> L[Selesai]
```

## 2.8 Flowchart perhitungan IPK

```mermaid
flowchart TD
    A[Mulai hitung IPK] --> B[Ambil semua mata kuliah yang lulus dan tertulis dalam nilai]
    B --> C[Validasi data akademik]
    C --> D[Ambil bobot setiap nilai]
    D --> E[Hitung total mutu = Σ(SKS × bobot)]
    E --> F[Hitung total SKS keseluruhan]
    F --> G{Total SKS > 0?}
    G -- Tidak --> H[IPK = 0]
    G -- Ya --> I[IPK = total mutu / total SKS]
    H --> J[Tampilkan hasil IPK]
    I --> J
    J --> K[Selesai]
```

## 2.9 Flowchart pembuatan transkrip

```mermaid
flowchart TD
    A[Admin/mahasiswa buka transkrip] --> B[Ambil data mahasiswa]
    B --> C[Ambil data program studi]
    C --> D[Ambil seluruh riwayat nilai]
    D --> E[Hitung total SKS dan IPK]
    E --> F[Siapkan layout transkrip]
    F --> G[Tambahkan logo, identitas, tabel nilai]
    G --> H{Cetak atau preview?}
    H -- Cetak --> I[Gunakan CSS print]
    H -- Preview --> J[Tampilkan halaman transkrip]
    I --> K[Selesai]
    J --> K
```

## 2.10 Flowchart laporan

```mermaid
flowchart TD
    A[Admin buka menu laporan] --> B[Pilih jenis laporan]
    B --> C{Laporan apa?}
    C -- Rekap nilai --> D[Ambil data nilai sesuai filter]
    C -- IPK/IPS --> E[Ambil statistik akademik]
    C -- Transkrip --> F[Ambil data transkrip]
    D --> G[Format laporan]
    E --> G
    F --> G
    G --> H{Export atau print?}
    H -- Print --> I[Generate HTML/Cetak]
    H -- Excel/PDF --> J[Generate file export]
    I --> K[Selesai]
    J --> K
```
