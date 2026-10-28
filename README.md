# Rimbun Kecil - Toko Tanaman Hias (versi tanpa database)

## Cara menjalankan
Tanpa MySQL. Cukup PHP 8.1 atau lebih baru.
- XAMPP/Laragon: letakkan folder di `htdocs`/`www`, buka `http://localhost/rimbun-kecil-tanpa-db/`
- Atau terminal: `php -S localhost:8000` di dalam folder ini.

## Penyimpanan data
- Produk, kategori, ongkir, layanan: array di `includes/data.php` (ubah produk dengan mengedit file ini).
- Akun & pesanan: file JSON di folder `data/` (dibuat otomatis saat ada yang daftar/memesan).
- Keterbatasan: stok di array tidak berkurang otomatis saat ada pesanan, dan tidak ada panel admin.

## Struktur
- includes/ : data, fungsi, auth, header, footer, kartu_produk
- assets/   : css/style.css, js/app.js
- Halaman   : index, katalog, produk, keranjang, checkout, pesanan, perawatan, tentang, kontak, login, daftar, logout, akun
