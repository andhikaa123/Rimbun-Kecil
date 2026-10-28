<?php
// ============================================
// HALAMAN TENTANG TOKO
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$judulHalaman = "Tentang Kami";
$halamanAktif = "tentang.php"; // dipakai header.php untuk menandai menu aktif
$deskripsiHalaman = "Kenali Rimbun Kecil: kebun tanaman hias di Denpasar, Bali, dengan layanan antar, konsultasi, dan garansi tanaman sehat.";

$jumlahProduk = count($produk);
$jumlahKategori = count($kategori);

require __DIR__ . '/includes/header.php';
?>

<section class="bg-lumut-800 text-white pola-daun">
    <div class="max-w-6xl mx-auto px-4 py-14 md:py-20">
        <h1 class="font-display font-extrabold text-4xl md:text-5xl max-w-2xl leading-tight">Berawal dari satu pot monstera di teras</h1>
        <p class="mt-4 text-lumut-100 text-lg max-w-2xl">
            <?= e($namaToko) ?> lahir dari hobi merawat tanaman di halaman rumah kecil di Denpasar. Kini kami merawat
            <?= $jumlahProduk ?> jenis tanaman dan perlengkapan dalam <?= $jumlahKategori ?> kategori, dan mengantarnya ke rumah-rumah di seluruh Bali.
        </p>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid md:grid-cols-2 gap-8 mb-14">
        <div>
            <h2 class="font-display font-bold text-2xl mb-3">Cerita kami</h2>
            <p class="text-lumut-800 leading-relaxed mb-3">
                Kami percaya tanaman tidak harus rewel. Setiap tanaman yang kami jual sudah melewati masa adaptasi
                di kebun, jadi saat sampai di rumahmu ia sudah kuat dan siap tumbuh.
            </p>
            <p class="text-lumut-800 leading-relaxed">
                Pot terakota kami dibuat pengrajin lokal Bali, dan media tanam kami racik sendiri dari sekam bakar, cocopeat, dan kompos.
            </p>
        </div>

        <div class="bg-white border border-lumut-100 rounded-2xl p-6">
            <h2 class="font-display font-bold text-2xl mb-4">Kunjungi kebun kami</h2>
            <dl class="space-y-3 text-sm">
                <div><dt class="font-bold">Alamat</dt><dd><?= e($kontak["alamat"]) ?></dd></div>
                <div><dt class="font-bold">Jam buka</dt><dd><?= e($kontak["jam"]) ?></dd></div>
                <div><dt class="font-bold">WhatsApp</dt><dd><?= e($kontak["tampilWa"]) ?></dd></div>
                <div><dt class="font-bold">Instagram</dt><dd><?= e($kontak["instagram"]) ?></dd></div>
            </dl>
            <a href="kontak.php" class="inline-block mt-5 bg-lumut-800 hover:bg-lumut-700 text-white font-bold px-6 py-2 rounded-full">Hubungi kami</a>
        </div>
    </div>

    <h2 class="font-display font-bold text-3xl mb-1">Layanan kami</h2>
    <p class="text-lumut-700 mb-6">Lebih dari sekadar jual tanaman.</p>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($layanan as $l): ?>
            <article class="bg-white border border-lumut-100 rounded-2xl p-6">
                <p class="text-4xl mb-3" aria-hidden="true"><?= $l["ikon"] ?></p>
                <h3 class="font-display font-bold text-lg mb-1"><?= e($l["nama"]) ?></h3>
                <p class="text-sm text-lumut-800 leading-relaxed"><?= e($l["ket"]) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
