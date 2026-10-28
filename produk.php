<?php
// ============================================
// DETAIL PRODUK: produk.php?kode=T001
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$kode = $_GET["kode"] ?? "";
$halamanAktif = "katalog.php";

if (!isset($produk[$kode])) {
    http_response_code(404);
    $judulHalaman = "Produk tidak ditemukan";
    require __DIR__ . '/includes/header.php';
    ?>
    <div class="max-w-xl mx-auto px-4 py-20 text-center">
        <p class="text-6xl mb-4">🥀</p>
        <h1 class="font-display font-extrabold text-3xl mb-2">Produk tidak ditemukan</h1>
        <p class="text-lumut-700 mb-6">Kode produk yang kamu buka tidak ada di katalog kami.</p>
        <a href="katalog.php" class="bg-lumut-800 text-white font-bold px-6 py-3 rounded-full">Kembali ke katalog</a>
    </div>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$item         = $produk[$kode];
$hargaAkhir   = hargaSetelahDiskon($item["harga"], $item["diskon"]);
$habis        = $item["stok"] === 0;
$judulHalaman = $item["nama"];
$deskripsiHalaman = $item["deskripsi"];

// Produk lain dari kategori yang sama
$terkait = array_filter($produk, fn($p, $k) => $p["kategori"] === $item["kategori"] && $k !== $kode, ARRAY_FILTER_USE_BOTH);
$terkait = array_slice($terkait, 0, 3, true);
$adaPerawatan = $item["tingkat"] !== "-";

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <nav class="text-sm text-lumut-700 mb-6" aria-label="Jejak halaman">
        <a href="katalog.php" class="hover:underline">Katalog</a> /
        <a href="katalog.php?kategori=<?= urlencode($item['kategori']) ?>" class="hover:underline"><?= e($item["kategori"]) ?></a> /
        <span><?= e($item["nama"]) ?></span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10">
        <div class="bg-white border border-lumut-100 rounded-3xl text-center text-[9rem] py-20 relative" aria-hidden="true">
            <?= $item["ikon"] ?>
            <?php if ($item["diskon"] > 0): ?>
                <span class="absolute top-5 left-5 bg-canang-400 text-lumut-900 text-sm font-bold px-3 py-1 rounded-full">Diskon <?= $item["diskon"] ?>%</span>
            <?php endif; ?>
        </div>

        <div>
            <p class="text-sm text-lumut-600 font-semibold">Kode <?= e($kode) ?></p>
            <h1 class="font-display font-extrabold text-4xl mb-3"><?= e($item["nama"]) ?></h1>

            <?php if ($item["diskon"] > 0): ?>
                <p class="text-stone-400 line-through"><?= formatRupiah($item["harga"]) ?></p>
            <?php endif; ?>
            <p class="text-3xl font-bold text-lumut-700 mb-4"><?= formatRupiah($hargaAkhir) ?></p>

            <div class="flex flex-wrap gap-2 mb-5 text-xs font-semibold">
                <span class="px-3 py-1 rounded-full <?= warnaStok($item['stok']) ?>"><?= labelStok($item["stok"]) ?></span>
                <?php if ($adaPerawatan): ?>
                    <span class="px-3 py-1 rounded-full <?= warnaTingkat($item['tingkat']) ?>">Perawatan <?= strtolower(e($item["tingkat"])) ?></span>
                <?php endif; ?>
            </div>

            <p class="text-lumut-800 leading-relaxed max-w-prose mb-6"><?= e($item["deskripsi"]) ?></p>

            <form action="keranjang.php" method="post" class="flex flex-wrap items-center gap-3 mb-8">
                <input type="hidden" name="aksi" value="tambah">
                <input type="hidden" name="kode" value="<?= e($kode) ?>">
                <input type="hidden" name="kembali" value="keranjang.php">
                <?php if (!$habis): ?>
                    <div data-jumlah class="flex items-center border border-lumut-200 rounded-full bg-white">
                        <button type="button" data-langkah="-1" class="w-10 h-10 text-xl" aria-label="Kurangi">&minus;</button>
                        <input type="number" name="jumlah" value="1" min="1" max="<?= $item['stok'] ?>" class="w-12 text-center bg-transparent" aria-label="Jumlah">
                        <button type="button" data-langkah="1" class="w-10 h-10 text-xl" aria-label="Tambah">+</button>
                    </div>
                <?php endif; ?>
                <button type="submit" <?= $habis ? 'disabled' : '' ?>
                    class="font-bold px-8 py-3 rounded-full <?= $habis ? 'bg-stone-200 text-stone-500 cursor-not-allowed' : 'bg-lumut-800 text-white hover:bg-lumut-700' ?>">
                    <?= $habis ? 'Stok habis' : 'Masukkan ke keranjang' ?>
                </button>
                <?php if ($habis): ?>
                    <a class="text-sm font-semibold underline" target="_blank" rel="noopener"
                       href="<?= e(linkWhatsapp($kontak, 'Halo, kapan ' . $item['nama'] . ' tersedia lagi?')) ?>">Tanya kapan restock</a>
                <?php endif; ?>
            </form>

            <dl class="bg-white border border-lumut-100 rounded-2xl divide-y divide-lumut-100 text-sm">
                <div class="flex justify-between gap-4 px-4 py-3"><dt class="text-lumut-600">Ukuran</dt><dd class="font-semibold text-right"><?= e($item["ukuran"]) ?></dd></div>
                <?php if ($adaPerawatan): ?>
                    <div class="flex justify-between gap-4 px-4 py-3"><dt class="text-lumut-600">Cahaya</dt><dd class="font-semibold text-right"><?= e($item["cahaya"]) ?></dd></div>
                    <div class="flex justify-between gap-4 px-4 py-3"><dt class="text-lumut-600">Penyiraman</dt><dd class="font-semibold text-right"><?= e($item["air"]) ?></dd></div>
                <?php endif; ?>
            </dl>
        </div>
    </div>

    <?php if ($terkait): ?>
        <h2 class="font-display font-bold text-2xl mt-16 mb-5">Lainnya di <?= e($item["kategori"]) ?></h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($terkait as $kode => $item): ?>
                <?php require __DIR__ . '/includes/kartu_produk.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
