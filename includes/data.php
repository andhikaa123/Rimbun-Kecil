<?php
// ============================================
// DATA TOKO RIMBUN KECIL
// Data toko. Versi tanpa database: semua data disimpan di array. Akun & pesanan di folder data/ (JSON).
// ============================================

// Session dipakai untuk keranjang belanja & nota terakhir.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$namaToko = "Rimbun Kecil";
$tagline  = "Hutan mungil untuk rumahmu";

$kontak = [
    "whatsapp" => "6281234567890",              // format internasional tanpa +
    "tampilWa" => "0812-3456-7890",
    "email"    => "halo@rimbunkecil.id",
    "alamat"   => "Jl. Raya Sesetan No. 88, Denpasar Selatan, Bali",
    "jam"      => "Setiap hari, 08.00 - 18.00 WITA",
    "instagram" => "@rimbunkecil.bali",
];

// Ongkos kirim per wilayah (rupiah)
$zonaKirim = [
    "Denpasar"  => 15000,
    "Badung"    => 20000,
    "Gianyar"   => 25000,
    "Tabanan"   => 30000,
    "Luar Bali" => 60000,
];
$batasGratisOngkir = 300000;

$metodeBayar = [
    "transfer" => "Transfer bank",
    "qris"     => "QRIS",
    "cod"      => "Bayar di tempat (khusus Denpasar & Badung)",
];

// Kategori: nama => ikon & keterangan singkat
$kategori = [
    "Daun Hias"          => ["ikon" => "🌿", "ket" => "Corak daun yang jadi pusat perhatian ruangan"],
    "Sukulen & Kaktus"   => ["ikon" => "🌵", "ket" => "Tahan lupa disiram, cocok untuk meja kerja"],
    "Tanaman Gantung"    => ["ikon" => "🍃", "ket" => "Menjuntai manis di teras dan jendela"],
    "Bunga"              => ["ikon" => "🌸", "ket" => "Warna cerah untuk sudut yang sepi"],
    "Pot & Media"        => ["ikon" => "🏺", "ket" => "Pot terakota dan tanah siap pakai"],
];

// Array multidimensi: kode produk => data produk (array asosiatif)
$produk = [
    "T001" => [
        "nama" => "Monstera Deliciosa", "kategori" => "Daun Hias",
        "harga" => 185000, "diskon" => 10, "stok" => 14, "ikon" => "🌿",
        "tingkat" => "Mudah", "cahaya" => "Terang, tidak kena matahari langsung",
        "air" => "Seminggu sekali", "ukuran" => "Pot 20 cm, tinggi sekitar 40 cm",
        "unggulan" => true,
        "deskripsi" => "Daunnya besar dengan celah khas yang makin banyak seiring tumbuh. Tumbuh cepat dan pemaaf, jadi pilihan pertama yang aman untuk pemula.",
    ],
    "T002" => [
        "nama" => "Lidah Mertua Zeylanica", "kategori" => "Daun Hias",
        "harga" => 65000, "diskon" => 0, "stok" => 40, "ikon" => "🪴",
        "tingkat" => "Mudah", "cahaya" => "Teduh sampai terang",
        "air" => "Dua minggu sekali", "ukuran" => "Pot 15 cm, tinggi sekitar 35 cm",
        "unggulan" => true,
        "deskripsi" => "Tegak, kokoh, dan nyaris tidak rewel. Cocok untuk kamar, kantor, atau sudut yang jarang kena cahaya.",
    ],
    "T003" => [
        "nama" => "Aglaonema Red Sumatra", "kategori" => "Daun Hias",
        "harga" => 120000, "diskon" => 15, "stok" => 4, "ikon" => "🌱",
        "tingkat" => "Mudah", "cahaya" => "Cahaya sedang",
        "air" => "Saat tanah bagian atas kering", "ukuran" => "Pot 18 cm, tinggi sekitar 30 cm",
        "unggulan" => false,
        "deskripsi" => "Daun hijau dengan tepi merah muda yang menyala. Tahan di dalam ruangan dan tampil beda di antara tanaman hijau lain.",
    ],
    "T004" => [
        "nama" => "Kalatea Orbifolia", "kategori" => "Daun Hias",
        "harga" => 250000, "diskon" => 0, "stok" => 2, "ikon" => "🌿",
        "tingkat" => "Sulit", "cahaya" => "Terang, tanpa sinar langsung",
        "air" => "Jaga media tetap lembap", "ukuran" => "Pot 15 cm, tinggi sekitar 30 cm",
        "unggulan" => false,
        "deskripsi" => "Daun bulat lebar bergaris perak. Suka udara lembap, cocok bagi yang sudah terbiasa merawat tanaman.",
    ],
    "T005" => [
        "nama" => "Echeveria Mini (Set 3)", "kategori" => "Sukulen & Kaktus",
        "harga" => 55000, "diskon" => 0, "stok" => 25, "ikon" => "🪷",
        "tingkat" => "Mudah", "cahaya" => "Matahari pagi atau cahaya terang",
        "air" => "Dua minggu sekali", "ukuran" => "3 pot kecil, diameter 6 cm",
        "unggulan" => true,
        "deskripsi" => "Tiga roset mungil dengan warna berbeda. Cantik berjejer di meja kerja atau kusen jendela.",
    ],
    "T006" => [
        "nama" => "Kaktus Gymnocalycium", "kategori" => "Sukulen & Kaktus",
        "harga" => 35000, "diskon" => 20, "stok" => 0, "ikon" => "🌵",
        "tingkat" => "Mudah", "cahaya" => "Matahari pagi",
        "air" => "Tiga minggu sekali", "ukuran" => "Pot 8 cm",
        "unggulan" => false,
        "deskripsi" => "Kaktus bulat berwarna cerah yang awet dan hemat perawatan. Stok sedang habis, cek lagi minggu depan.",
    ],
    "T007" => [
        "nama" => "Pothos Golden Gantung", "kategori" => "Tanaman Gantung",
        "harga" => 45000, "diskon" => 0, "stok" => 30, "ikon" => "🍃",
        "tingkat" => "Mudah", "cahaya" => "Teduh sampai terang",
        "air" => "Seminggu sekali", "ukuran" => "Pot gantung 15 cm, sulur sekitar 30 cm",
        "unggulan" => true,
        "deskripsi" => "Sulur hijau berbercak kuning yang menjuntai ke bawah. Bisa juga dirawat di dalam botol berisi air.",
    ],
    "T008" => [
        "nama" => "String of Pearls", "kategori" => "Tanaman Gantung",
        "harga" => 75000, "diskon" => 10, "stok" => 3, "ikon" => "🌱",
        "tingkat" => "Sedang", "cahaya" => "Terang",
        "air" => "Saat tanah benar-benar kering", "ukuran" => "Pot gantung 10 cm",
        "unggulan" => false,
        "deskripsi" => "Untaian daun bulat seperti manik-manik. Tidak suka tanah basah, jadi siram secukupnya.",
    ],
    "T009" => [
        "nama" => "Anggrek Bulan Putih", "kategori" => "Bunga",
        "harga" => 150000, "diskon" => 0, "stok" => 12, "ikon" => "🌸",
        "tingkat" => "Sedang", "cahaya" => "Terang, teduh dari siang",
        "air" => "Dua kali seminggu, jangan sampai menggenang", "ukuran" => "Pot 12 cm, 2 tangkai bunga",
        "unggulan" => true,
        "deskripsi" => "Bunga putih bersih yang bertahan berminggu-minggu. Cocok sebagai hadiah atau pajangan ruang tamu.",
    ],
    "T010" => [
        "nama" => "Anthurium Merah", "kategori" => "Bunga",
        "harga" => 210000, "diskon" => 5, "stok" => 6, "ikon" => "🌺",
        "tingkat" => "Sedang", "cahaya" => "Cahaya sedang",
        "air" => "Tiga kali seminggu", "ukuran" => "Pot 18 cm, tinggi sekitar 35 cm",
        "unggulan" => false,
        "deskripsi" => "Kelopak merah mengilap yang tahan lama. Suka udara sedikit lembap dan tidak kena sinar langsung.",
    ],
    "T011" => [
        "nama" => "Pot Terakota Bali 20 cm", "kategori" => "Pot & Media",
        "harga" => 40000, "diskon" => 0, "stok" => 50, "ikon" => "🏺",
        "tingkat" => "-", "cahaya" => "-",
        "air" => "-", "ukuran" => "Diameter 20 cm, tinggi 18 cm",
        "unggulan" => false,
        "deskripsi" => "Pot tanah liat buatan pengrajin Bali. Berpori, jadi akar bisa bernapas dan tanah lebih cepat kering.",
    ],
    "T012" => [
        "nama" => "Media Tanam Premium 5 L", "kategori" => "Pot & Media",
        "harga" => 30000, "diskon" => 0, "stok" => 35, "ikon" => "🧺",
        "tingkat" => "-", "cahaya" => "-",
        "air" => "-", "ukuran" => "Kemasan 5 liter",
        "unggulan" => false,
        "deskripsi" => "Campuran sekam bakar, cocopeat, dan kompos. Gembur dan drainasenya baik untuk tanaman hias dalam pot.",
    ],
];

// Tips perawatan (dipakai di halaman perawatan.php)
$tips = [
    ["ikon" => "💧", "judul" => "Siram saat tanah kering",
     "isi" => "Tekan jari 2 cm ke dalam tanah. Kalau masih lembap, tunda dulu. Terlalu banyak air adalah penyebab utama tanaman mati."],
    ["ikon" => "☀️", "judul" => "Kenali kebutuhan cahaya",
     "isi" => "Daun pucat atau memanjang tanda kurang cahaya. Daun cokelat dan kering tanda terlalu terik. Geser posisi pot sedikit demi sedikit."],
    ["ikon" => "🪴", "judul" => "Pot harus punya lubang",
     "isi" => "Lubang drainase mencegah akar membusuk. Jika memakai pot hias tanpa lubang, taruh tanaman dalam pot plastik di dalamnya."],
    ["ikon" => "🌱", "judul" => "Beri pupuk secukupnya",
     "isi" => "Cukup sebulan sekali pada musim tumbuh dengan dosis setengah anjuran kemasan. Tanaman baru jangan dipupuk dulu selama sebulan."],
    ["ikon" => "🐛", "judul" => "Periksa daun rutin",
     "isi" => "Lihat bagian bawah daun seminggu sekali. Jika ada kutu, usap dengan kain lembap dan semprot air sabun encer."],
    ["ikon" => "🔄", "judul" => "Ganti pot saat akar penuh",
     "isi" => "Jika akar keluar dari lubang bawah atau tanaman cepat kering, pindahkan ke pot yang diameternya 2-4 cm lebih besar."],
];

// Layanan toko (dipakai di halaman tentang.php)
$layanan = [
    ["ikon" => "🚚", "nama" => "Antar ke seluruh Bali",   "ket" => "Pengiriman khusus tanaman dengan kemasan berlapis. Gratis ongkir untuk belanja di atas Rp 300.000."],
    ["ikon" => "🌿", "nama" => "Konsultasi tanaman",       "ket" => "Bingung memilih? Ceritakan ruanganmu lewat WhatsApp, kami carikan tanaman yang cocok."],
    ["ikon" => "🪴", "nama" => "Pindah pot",               "ket" => "Beli tanaman dan pot, kami pindahkan langsung supaya tanaman tidak stres."],
    ["ikon" => "🎁", "nama" => "Kemasan hadiah",           "ket" => "Tambahkan catatan saat memesan, tanaman dikemas rapi lengkap dengan kartu ucapan."],
    ["ikon" => "🛠️", "nama" => "Garansi tanaman sehat",    "ket" => "Tanaman rusak saat sampai? Kirim foto dalam 24 jam dan kami ganti baru."],
    ["ikon" => "🏡", "nama" => "Kunjungan ke kebun",       "ket" => "Datang langsung, pilih tanaman favoritmu, dan lihat cara kami merawatnya."],
];
