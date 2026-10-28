<?php
// ============================================
// TIPS PERAWATAN
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$judulHalaman = "Tips Perawatan";
$halamanAktif = "perawatan.php";
$deskripsiHalaman = "Panduan merawat tanaman hias untuk pemula: penyiraman, cahaya, pot, pupuk, dan hama.";

// Tanaman paling mudah dirawat, untuk rekomendasi pemula
$pemula = array_slice(array_filter($produk, fn($p) => $p["tingkat"] === "Mudah"), 0, 3, true);

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-extrabold text-4xl mb-1">Tips perawatan</h1>
    <p class="text-lumut-700 mb-8 max-w-xl">Enam kebiasaan sederhana yang menjaga tanamanmu tetap sehat, ditulis oleh tim kebun kami.</p>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($tips as $t): ?>
            <article class="bg-white border border-lumut-100 rounded-2xl p-6">
                <p class="text-4xl mb-3" aria-hidden="true"><?= $t["ikon"] ?></p>
                <h2 class="font-display font-bold text-lg mb-2"><?= e($t["judul"]) ?></h2>
                <p class="text-sm text-lumut-800 leading-relaxed"><?= e($t["isi"]) ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <h2 class="font-display font-bold text-2xl mt-14 mb-5">Tanaman yang cocok untuk pemula</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($pemula as $kode => $item): ?>
            <?php require __DIR__ . '/includes/kartu_produk.php'; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
