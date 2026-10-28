<?php
// ============================================
// KATALOG: filter kategori, pencarian, urutan
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$judulHalaman = "Katalog";
$halamanAktif = "katalog.php";

// Ambil input dari URL (GET)
$kategoriDipilih = trim($_GET["kategori"] ?? "");
$kata            = trim($_GET["q"] ?? "");
$urut            = $_GET["urut"] ?? "";

// Filter kategori: "" berarti tampilkan semua (nilainya diambil dari URL ?kategori=...)
if ($kategoriDipilih !== "" && !isset($kategori[$kategoriDipilih])) {
    $kategoriDipilih = "";
}
$opsiUrut = ["" => "Urutan toko", "termurah" => "Harga terendah", "termahal" => "Harga tertinggi", "diskon" => "Diskon terbesar"];
if (!isset($opsiUrut[$urut])) {
    $urut = "";
}

// Ringkasan toko (dihitung dari SELURUH produk, bukan hasil filter)
$jumlahProduk      = count($produk);
$jumlahDiskon      = hitungProdukDiskon($produk);
$nilaiStok         = hitungNilaiStok($produk);
$termurah          = cariProdukTermurah($produk);
$rataHarga         = hitungRataRataHarga($produk);
$jumlahPerKategori = hitungJumlahPerKategori($produk);

$hasil = cariProduk($produk, $kategoriDipilih, $kata, $urut);

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-extrabold text-4xl mb-1">Katalog tanaman</h1>
    <p class="text-lumut-700 mb-1">Pilih berdasarkan kategori, atau cari nama tanaman.</p>
    <p class="text-sm text-lumut-700 mb-1">Rata-rata harga produk: <strong><?= formatRupiah($rataHarga) ?></strong></p>
    <p class="text-sm text-lumut-700 mb-6">
        <?= e(implode(" · ", array_map(fn($nama, $jumlah) => "$nama ($jumlah)", array_keys($jumlahPerKategori), $jumlahPerKategori))) ?>
    </p>

    <!-- Ringkasan -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-lumut-100 rounded-2xl p-4">
            <p class="text-sm text-lumut-700">Jumlah produk</p>
            <p class="text-2xl font-bold"><?= $jumlahProduk ?></p>
        </div>
        <div class="bg-white border border-lumut-100 rounded-2xl p-4">
            <p class="text-sm text-lumut-700">Sedang diskon</p>
            <p class="text-2xl font-bold"><?= $jumlahDiskon ?> produk</p>
        </div>
        <div class="bg-white border border-lumut-100 rounded-2xl p-4">
            <p class="text-sm text-lumut-700">Total nilai stok</p>
            <p class="text-2xl font-bold"><?= formatRupiah($nilaiStok) ?></p>
        </div>
        <div class="bg-white border border-lumut-100 rounded-2xl p-4">
            <p class="text-sm text-lumut-700">Produk termurah</p>
            <?php if ($termurah): ?>
                <p class="text-2xl font-bold"><?= formatRupiah($termurah["harga"]) ?></p>
                <p class="text-xs text-lumut-600"><?= e($termurah["nama"]) ?></p>
            <?php else: ?>
                <p class="text-2xl font-bold">-</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter -->
    <form method="get" class="bg-white border border-lumut-100 rounded-2xl p-4 grid gap-3 md:grid-cols-[1fr_auto_auto_auto] mb-6">
        <label class="sr-only" for="q">Cari tanaman</label>
        <input id="q" type="search" name="q" value="<?= e($kata) ?>" placeholder="Cari, misalnya monstera"
               class="border border-lumut-200 rounded-xl px-4 py-2 w-full">
        <label class="sr-only" for="kategori">Kategori</label>
        <select id="kategori" name="kategori" class="border border-lumut-200 rounded-xl px-3 py-2 bg-white">
            <option value="">Semua kategori</option>
            <?php foreach ($kategori as $nama => $info): ?>
                <option value="<?= e($nama) ?>" <?= $nama === $kategoriDipilih ? 'selected' : '' ?>><?= e($nama) ?></option>
            <?php endforeach; ?>
        </select>
        <label class="sr-only" for="urut">Urutkan</label>
        <select id="urut" name="urut" class="border border-lumut-200 rounded-xl px-3 py-2 bg-white">
            <?php foreach ($opsiUrut as $nilai => $teks): ?>
                <option value="<?= $nilai ?>" <?= $nilai === $urut ? 'selected' : '' ?>><?= $teks ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-lumut-800 hover:bg-lumut-700 text-white font-bold px-6 py-2 rounded-xl">Terapkan</button>
    </form>

    <!-- Chip kategori -->
    <div class="flex flex-wrap gap-2 mb-6 text-sm font-semibold">
        <a href="katalog.php" class="px-4 py-2 rounded-full <?= $kategoriDipilih === '' ? 'bg-lumut-800 text-white' : 'bg-white border border-lumut-100 hover:bg-lumut-100' ?>">Semua</a>
        <?php foreach ($kategori as $nama => $info): ?>
            <a href="katalog.php?kategori=<?= urlencode($nama) ?>" class="px-4 py-2 rounded-full <?= $nama === $kategoriDipilih ? 'bg-lumut-800 text-white' : 'bg-white border border-lumut-100 hover:bg-lumut-100' ?>">
                <?= $info["ikon"] ?> <?= e($nama) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <p class="text-sm text-lumut-700 mb-4">Menampilkan <?= count($hasil) ?> produk<?= $kata !== '' ? ' untuk "' . e($kata) . '"' : '' ?>.</p>

    <?php if (count($hasil) === 0): ?>
        <div class="bg-white border border-lumut-100 rounded-2xl p-10 text-center">
            <p class="text-5xl mb-3">🔍</p>
            <p class="font-display font-bold text-xl">Belum ada tanaman yang cocok</p>
            <p class="text-lumut-700 mt-1">Coba kata kunci lain atau hapus filter kategori.</p>
            <a href="katalog.php" class="inline-block mt-5 bg-lumut-800 text-white font-bold px-6 py-2 rounded-full">Tampilkan semua</a>
        </div>
    <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($hasil as $kode => $item): ?>
                <?php require __DIR__ . '/includes/kartu_produk.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
