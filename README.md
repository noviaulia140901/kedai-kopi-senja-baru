# UKK Kedai Kopi Senja

## 1. Ringkasan Sistem

**Kedai Kopi Senja** merupakan aplikasi berbasis website untuk membantu pengelolaan operasional kedai kopi.

Aplikasi ini digunakan oleh:
- **Pelanggan:** melakukan registrasi dan pemesanan melalui website.
- **Kasir:** mengelola pesanan pelanggan dan memantau status pesanan.
- **Manajer:** mengelola menu, kategori, bahan baku, resep, stok, dan laporan.

Sistem dibuat untuk membantu proses pengelolaan kedai agar data operasional lebih terorganisasi.

## 2. Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP | Bahasa pemrograman backend |
| Laravel 13 | Framework aplikasi |
| MySQL | Database |
| Blade | Template tampilan |
| Tailwind CSS | Styling antarmuka |
| JavaScript | Interaksi antarmuka |
| Vite | Pengelolaan asset frontend |
| Composer | Dependency PHP |
| Node.js dan NPM | Dependency frontend |

**Platform:** Website.

**APK Android:** Tidak dikembangkan pada proyek ini.

## 3. Cara Menjalankan Server

### Persyaratan
- PHP yang kompatibel dengan Laravel 13.
- Composer.
- MySQL atau MariaDB.
- Node.js dan NPM.

### Instalasi

Clone repository:

```bash
git clone https://github.com/noviaulia140901/kedai-kopi-senja.git
cd kedai-kopi-senja
```

Install dependency:

```bash
composer install
npm install
```

Salin konfigurasi:

```powershell
Copy-Item .env.example .env
```

Buat database MySQL bernama `kedai_kopi_senja`.

Generate application key:

```bash
php artisan key:generate
```

Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Impor data CSV:

```bash
php artisan app:import-kedai-csv
```

Build frontend:

```bash
npm run build
```

Jalankan server:

```bash
php artisan serve
```

Buka alamat berikut di browser:

`http://127.0.0.1:8000`

### Konfigurasi Supplier API

Atur konfigurasi berikut pada file `.env`:

```env
SUPPLIER_API_URL=https://ukksmkn1katapang.next-it.co.id/kafe
SUPPLIER_API_KEY=
```

Masukkan API key yang diberikan oleh penyedia layanan. API key bersifat rahasia dan tidak boleh dibagikan atau dimasukkan ke repository.

Integrasi Supplier API telah diuji dan berhasil memperbarui 20 harga bahan.

## 4. Cara Memasang APK

APK Android tidak dikembangkan dalam proyek ini. Oleh karena itu, tidak tersedia file APK maupun alamat server APK.

## 5. Akun Uji

Akun berikut dibuat otomatis melalui `DatabaseSeeder`.

| Role | Username | Email | Password |
|---|---|---|---|
| Kasir | kasir | kasir@kedaikopisenja.test | KasirSenja123 |
| Manajer | manajer | manajer@kedaikopisenja.test | ManajerSenja123 |

**Pelanggan:** Tidak disediakan melalui seeder. Pelanggan dapat membuat akun melalui halaman registrasi website.

Catatan: Akun dan password di atas digunakan untuk pengujian lokal, bukan untuk penggunaan produksi.

## 6. Letak Berkas Penting

| Berkas/Folder | Keterangan |
|---|---|
| `database/migrations/` | Struktur tabel database |
| `database/seeders/` | Data awal aplikasi |
| `app/Console/Commands/ImportKedaiCsv.php` | Perintah impor CSV |
| `database/data/` | Lima file CSV sumber data |
| `app/Services/SupplierPriceService.php` | Proses integrasi Supplier API |
| `config/services.php` | Konfigurasi layanan eksternal |

## 7. Keputusan dan Aturan Pengolahan Data

### Pencocokan Nama Bahan
- Nama bahan dibandingkan menggunakan huruf kecil.
- Terdapat pengecualian pencocokan `biji kopi arabika` dengan `kopi arabika`.
- Pencocokan kemiripan otomatis untuk semua nama bahan tidak diterapkan.

### Pengolahan Satuan
- Satuan resep harus sama dengan satuan dasar bahan.
- Satuan stok demo `liter` dikonversi menjadi `ml` apabila satuan dasar bahan adalah `ml`.
- Satuan lain yang tidak sesuai akan menyebabkan proses impor gagal.

### Stok Awal
- Stok minimum awal ditetapkan sebesar `0`.
- Stok akhir dihitung dari stok awal ditambah stok demo.
- Proses impor menggunakan transaksi database.

### Harga Bahan
- Harga bahan diambil dari kolom harga per satuan dasar pada CSV.
- Pembaruan harga dari Supplier API hanya dilakukan jika kode bahan dan satuannya cocok.
- Jika pengambilan harga dari Supplier API gagal, sistem menggunakan harga terakhir yang tersimpan beserta tanggal pembaruannya.

## 8. Lembar Self-Check

Berdasarkan pengujian aplikasi, berikut klaim penerimaan UKK Kedai Kopi Senja.

| No | Butir Penerimaan | Klaim (Ya/Tidak) | Langkah Uji Singkat |
|---|---|---|---|
| 1 | Kelola bahan (satuan dasar, nama tidak ganda), menu (bisa dinonaktifkan), dan resep (multi bahan, takaran). | Ya | Login sebagai Manajer, kelola bahan, menu, dan resep. Periksa validasi nama, status menu, serta penyimpanan beberapa bahan dan takarannya. |
| 2 | Pencatatan stok masuk; batas minimum per bahan diatur Manajer dan daftar bahan menipis langsung mengikutinya. | Ya | Login sebagai Manajer, catat stok masuk, ubah batas minimum bahan, lalu periksa daftar bahan menipis. |
| 3 | Pesanan dengan beberapa item; stok bahan berkurang sesuai resep × jumlah porsi; menu tanpa resep atau nonaktif tidak dapat dipesan. | Ya | Buat pesanan dengan beberapa item dan jumlah porsi, periksa pengurangan stok, lalu coba pesan menu tanpa resep atau nonaktif. |
| 4 | Pesanan ditolak bila bahan tidak cukup, kebutuhan gabungan antar item dihitung benar (butir inti). | Ya | Buat pesanan beberapa menu yang menggunakan bahan sama hingga kebutuhan gabungan melebihi stok. Pastikan pesanan ditolak. |
| 5 | Menu otomatis berstatus Habis bila bahan tidak cukup untuk 1 porsi. | Ya | Kurangi stok bahan hingga tidak mencukupi satu porsi, kemudian periksa status menu. |
| 6 | Status pesanan berjenjang, loncat ditolak; batal hanya saat Baru, hanya oleh Kasir, dan stok kembali. | Ya | Uji perubahan status secara berurutan, coba loncat status, lalu batalkan pesanan Baru sebagai Kasir dan periksa pengembalian stok. |
| 7 | Struk: subtotal, service 5%, pajak 10% (urutan benar), total; struk lama tidak berubah bila harga jual menu diubah (butir inti). | Ya | Buat pesanan, periksa subtotal, service, pajak, dan total. Ubah harga menu, lalu buka kembali struk lama dan periksa nilainya. |
| 8 | HPP dan margin per menu dari resep. | Ya | Login sebagai Manajer, periksa perhitungan HPP dan margin menu berdasarkan resep serta harga bahan. |
| 9 | Laporan penjualan per menu, bahan menipis, margin per menu; hak akses: kelola menu, resep, bahan, stok masuk, dan laporan hanya Manajer (peran lain ditolak, juga bila halaman dibuka lewat alamat langsung); Kasir melihat daftar menu tanpa resep, HPP, margin; Manajer tidak membuat pesanan dan tidak mengubah statusnya. | Ya | Login sebagai Manajer untuk memeriksa laporan dan pengelolaan data. Login sebagai Kasir untuk memeriksa batas akses, termasuk mencoba membuka alamat halaman Manajer secara langsung. |
| 10 | Data awal CSV: satuan dinormalkan, nama bahan salah ketik di resep dicocokkan, bahan tidak dikenal tidak diimpor, batas minimum awal 0. | Ya | Jalankan perintah impor CSV, lalu periksa hasil pencocokan nama bahan, normalisasi satuan, bahan tidak dikenal, dan nilai stok minimum awal. |
| 11 | Integrasi Supplier (dengan API key), fallback harga terakhir + tanggal. | Ya | Isi API key, jalankan pembaruan harga Supplier API, periksa harga dan tanggal pembaruan. Periksa juga penggunaan harga terakhir ketika pengambilan harga gagal. |
| 12 | Akun pelanggan lengkap (APK): daftar sendiri dengan nomor HP unik + password minimal 8 karakter, login dengan nomor HP, ganti password, lupa password direset Kasir (wajib ganti saat login berikutnya), logout. | Tidak | APK Android tidak dikembangkan dalam proyek ini. |
| 13 | Pelanggan (APK) memesan dengan memilih nomor meja; aturan pesanan dan stok sama dengan pesanan dari Kasir; hanya boleh satu pesanan berstatus Baru pada satu waktu. | Tidak | APK Android tidak dikembangkan dalam proyek ini. |
| 14 | Pelanggan (APK) memantau status dan struk pesanannya sendiri; pesanan orang lain tidak bisa dibuka. | Tidak | APK Android tidak dikembangkan dalam proyek ini. |

**Keterangan:** Butir 4 dan 7 merupakan butir inti penerimaan UKK. Pengujian aplikasi dilakukan berdasarkan alur penggunaan dan hasil yang diperoleh selama pengembangan.

## 9. Fitur yang Belum Selesai dan Keterbatasan

- APK Android tidak dikembangkan dalam proyek ini sehingga persyaratan yang secara khusus ditujukan untuk APK pada butir 12–14 tidak terpenuhi.
- Fitur website untuk Pelanggan, Kasir, dan Manajer telah diuji dan berjalan.
- Import data CSV telah berhasil dilakukan.
- Integrasi Supplier API telah berhasil diuji dengan pembaruan 20 harga bahan.

## 10. Referensi dan Bantuan

### Referensi Teknologi
- PHP: https://www.php.net/docs.php
- Laravel 13: https://laravel.com/docs/13.x
- MySQL: https://dev.mysql.com/doc/
- Tailwind CSS: https://tailwindcss.com/docs
- Vite: https://vite.dev/guide/
- Composer: https://getcomposer.org/doc/
- Node.js: https://nodejs.org/docs/

### Bantuan Pengembangan
- **ChatGPT (OpenAI):** Digunakan sebagai alat bantu dalam memahami kode program, menyelesaikan kendala, memeriksa kesalahan, menyusun dokumentasi, serta memberikan arahan selama proses pengembangan dan pengujian.
- Dokumentasi resmi teknologi digunakan sebagai referensi dalam memahami framework, bahasa pemrograman, dan perangkat pengembangan.

---

**UKK Kedai Kopi Senja — 2026**
