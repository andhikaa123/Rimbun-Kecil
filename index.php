<?php
// ============================================
// BERANDA
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$judulHalaman = "Beranda";
$halamanAktif = "index.php";

$unggulan = array_filter($produk, fn($p) => $p["unggulan"]);
$jumlahProduk = count($produk);
$jumlahDiskon = hitungProdukDiskon($produk);

require __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="bg-lumut-800 text-white pola-daun">
    <div class="max-w-6xl mx-auto px-4 py-16 md:py-24 grid md:grid-cols-2 gap-10 items-center">
        <div class="hero-muncul">
            <h1 class="font-display font-extrabold text-4xl md:text-6xl leading-[1.05]">Hutan mungil untuk rumahmu</h1>
            <p class="mt-5 text-lumut-100 text-lg max-w-md">Tanaman hias sehat dari kebun kami di Denpasar, dikemas aman dan diantar ke seluruh Bali.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="katalog.php" class="bg-canang-400 hover:bg-canang-300 text-lumut-900 font-bold px-6 py-3 rounded-full">Lihat katalog</a>
                <a href="perawatan.php" class="border border-lumut-200 hover:bg-lumut-700 font-semibold px-6 py-3 rounded-full">Baca tips perawatan</a>
            </div>
            <p class="mt-6 text-sm text-lumut-200">Gratis ongkir untuk belanja di atas <?= formatRupiah($batasGratisOngkir) ?>.</p>
        </div>
        <div class="grid grid-cols-3 gap-3 text-center" aria-hidden="true">
            <div class="bg-lumut-700 rounded-3xl py-10 text-6xl">🌿</div>
            <div class="bg-canang-400 rounded-3xl py-14 text-6xl -mt-6">🌸</div>
            <div class="bg-lumut-600 rounded-3xl py-10 text-6xl">🌵</div>
            <div class="bg-lumut-600 rounded-3xl py-10 text-6xl">🍃</div>
            <div class="bg-lumut-700 rounded-3xl py-14 text-6xl -mt-6">🪴</div>
            <div class="bg-canang-300 rounded-3xl py-10 text-6xl">🌺</div>
        </div>
    </div>
</section>

<!-- Kategori -->
<section class="max-w-6xl mx-auto px-4 pt-14">
    <h2 class="font-display font-bold text-3xl mb-1">Mau cari yang mana?</h2>
    <p class="text-lumut-700 mb-6"><?= $jumlahProduk ?> tanaman dan perlengkapan, <?= $jumlahDiskon ?> di antaranya sedang diskon.</p>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <?php foreach ($kategori as $nama => $info): ?>
            <a href="katalog.php?kategori=<?= urlencode($nama) ?>" class="bg-white border border-lumut-100 hover:border-lumut-600 rounded-2xl p-5 flex flex-col gap-1">
                <span class="text-4xl" aria-hidden="true"><?= $info["ikon"] ?></span>
                <span class="font-display font-bold mt-2"><?= e($nama) ?></span>
                <span class="text-xs text-lumut-600"><?= hitungPerKategori($produk, $nama) ?> produk</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Produk unggulan -->
<section class="max-w-6xl mx-auto px-4 pt-14">
    <div class="flex items-end justify-between mb-6">
        <h2 class="font-display font-bold text-3xl">Favorit pelanggan</h2>
        <a href="katalog.php" class="text-sm font-semibold text-lumut-700 hover:underline">Lihat semua</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($unggulan as $kode => $item): ?>
            <?php require __DIR__ . '/includes/kartu_produk.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<!-- Alasan belanja -->
<section class="max-w-6xl mx-auto px-4 pt-14">
    <div class="bg-white border border-lumut-100 rounded-3xl p-8 grid md:grid-cols-3 gap-8">
        <div>
            <h3 class="font-display font-bold text-xl mb-2">Dirawat sebelum dikirim</h3>
            <p class="text-lumut-700 text-sm">Setiap tanaman dicek daun, akar, dan medianya oleh tim kebun sebelum masuk kardus.</p>
        </div>
        <div>
            <h3 class="font-display font-bold text-xl mb-2">Kemasan aman</h3>
            <p class="text-lumut-700 text-sm">Pot ditahan dengan kardus berlapis. Kalau tanaman rusak di jalan, kami ganti baru dengan bukti foto.</p>
        </div>
        <div>
            <h3 class="font-display font-bold text-xl mb-2">Ada panduan perawatan</h3>
            <p class="text-lumut-700 text-sm">Tiap produk dilengkapi kebutuhan cahaya dan air. Bingung? Tanya kami lewat WhatsApp.</p>
        </div>
    </div>
</section>

<!-- Ajakan -->
<section class="max-w-6xl mx-auto px-4 pt-14">
    <div class="bg-canang-400 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="font-display font-extrabold text-3xl">Belanja <?= formatRupiah(200000) ?> dapat diskon 10%</h2>
            <p class="mt-2 text-lumut-800">Naik ke <?= formatRupiah(500000) ?> dan diskonnya jadi 15%. Berlaku otomatis di keranjang.</p>
        </div>
        <a href="katalog.php?urut=diskon" class="bg-lumut-900 text-white font-bold px-6 py-3 rounded-full text-center hover:bg-lumut-800">Lihat produk diskon</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
