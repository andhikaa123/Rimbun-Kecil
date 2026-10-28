<?php
// ============================================
// KERANJANG BELANJA
// Form POST (tambah/ubah/hapus/kosongkan) diproses dulu, lalu halaman dialihkan.
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $aksi   = $_POST["aksi"] ?? "";
    $kode   = $_POST["kode"] ?? "";
    $jumlah = (int) ($_POST["jumlah"] ?? 1);
    $kembali = alamatAman($_POST["kembali"] ?? "", "keranjang.php");

    switch ($aksi) {
        case "tambah":
            setFlash(keranjangTambah($kode, $jumlah, $produk));
            break;
        case "ubah":
            keranjangUbah($kode, $jumlah, $produk);
            break;
        case "hapus":
            keranjangHapus($kode);
            setFlash("Produk dihapus dari keranjang.");
            break;
        case "kosongkan":
            keranjangKosongkan();
            setFlash("Keranjang dikosongkan.");
            break;
    }
    alihkan($kembali);
}

$judulHalaman = "Keranjang";
$halamanAktif = "keranjang.php";

$keranjang = keranjangAmbil();
$ringkasan = hitungRingkasan($keranjang, $produk, "", $zonaKirim, $batasGratisOngkir);

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-extrabold text-4xl mb-6">Keranjang belanja</h1>

    <?php if (!$keranjang): ?>
        <div class="bg-white border border-lumut-100 rounded-2xl p-12 text-center">
            <p class="text-6xl mb-3">🧺</p>
            <p class="font-display font-bold text-xl">Keranjangmu masih kosong</p>
            <p class="text-lumut-700 mt-1">Pilih satu tanaman untuk memulai hutan mungilmu.</p>
            <a href="katalog.php" class="inline-block mt-5 bg-lumut-800 text-white font-bold px-6 py-3 rounded-full">Lihat katalog</a>
        </div>
    <?php else: ?>
        <div class="grid lg:grid-cols-[1fr_22rem] gap-8 items-start">
            <div class="space-y-3">
                <?php foreach ($keranjang as $kode => $jumlah): ?>
                    <?php
                    if (!isset($produk[$kode])) { continue; }
                    $item       = $produk[$kode];
                    $hargaAkhir = hargaSetelahDiskon($item["harga"], $item["diskon"]);
                    ?>
                    <div class="bg-white border border-lumut-100 rounded-2xl p-4 flex flex-wrap items-center gap-4">
                        <div class="text-4xl bg-lumut-50 rounded-xl w-16 h-16 grid place-items-center" aria-hidden="true"><?= $item["ikon"] ?></div>
                        <div class="flex-1 min-w-40">
                            <a href="produk.php?kode=<?= urlencode($kode) ?>" class="font-display font-bold hover:underline"><?= e($item["nama"]) ?></a>
                            <p class="text-sm text-lumut-700"><?= formatRupiah($hargaAkhir) ?> per item</p>
                        </div>

                        <form action="keranjang.php" method="post" data-jumlah="otomatis" class="flex items-center border border-lumut-200 rounded-full">
                            <input type="hidden" name="aksi" value="ubah">
                            <input type="hidden" name="kode" value="<?= e($kode) ?>">
                            <button type="button" data-langkah="-1" class="w-9 h-9 text-lg" aria-label="Kurangi">&minus;</button>
                            <input type="number" name="jumlah" value="<?= $jumlah ?>" min="1" max="<?= $item['stok'] ?>" class="w-10 text-center bg-transparent" aria-label="Jumlah <?= e($item['nama']) ?>">
                            <button type="button" data-langkah="1" class="w-9 h-9 text-lg" aria-label="Tambah">+</button>
                        </form>

                        <p class="font-bold w-28 text-right"><?= formatRupiah($hargaAkhir * $jumlah) ?></p>

                        <form action="keranjang.php" method="post">
                            <input type="hidden" name="aksi" value="hapus">
                            <input type="hidden" name="kode" value="<?= e($kode) ?>">
                            <button type="submit" class="text-sm text-red-700 font-semibold hover:underline">Hapus</button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <form action="keranjang.php" method="post" class="pt-2">
                    <input type="hidden" name="aksi" value="kosongkan">
                    <button type="submit" class="text-sm text-lumut-700 hover:underline">Kosongkan keranjang</button>
                </form>
            </div>

            <aside class="bg-white border border-lumut-100 rounded-2xl p-6 lg:sticky lg:top-24">
                <h2 class="font-display font-bold text-xl mb-4">Ringkasan</h2>
                <div class="space-y-2 text-sm">
                    <p class="flex justify-between"><span>Total barang</span><span><?= $ringkasan["jumlahBarang"] ?> item</span></p>
                    <p class="flex justify-between"><span>Subtotal</span><span><?= formatRupiah($ringkasan["subtotal"]) ?></span></p>
                    <p class="flex justify-between text-lumut-700"><span>Diskon belanja</span><span>- <?= formatRupiah($ringkasan["potongan"]) ?></span></p>
                    <p class="flex justify-between text-lg font-bold border-t border-lumut-100 pt-3"><span>Total sementara</span><span><?= formatRupiah($ringkasan["total"]) ?></span></p>
                </div>
                <p class="text-xs text-lumut-700 mt-2">Ongkir dihitung di langkah berikutnya sesuai wilayah.</p>

                <?php
                $setelah = $ringkasan["subtotal"] - $ringkasan["potongan"];
                if ($ringkasan["potongan"] === 0): ?>
                    <p class="mt-4 text-sm bg-canang-300/40 rounded-xl p-3">Tambah belanja <?= formatRupiah(200000 - $ringkasan["subtotal"]) ?> lagi untuk diskon 10%.</p>
                <?php elseif ($setelah < $batasGratisOngkir): ?>
                    <p class="mt-4 text-sm bg-canang-300/40 rounded-xl p-3">Tambah <?= formatRupiah($batasGratisOngkir - $setelah) ?> lagi untuk gratis ongkir.</p>
                <?php else: ?>
                    <p class="mt-4 text-sm bg-lumut-100 rounded-xl p-3">Kamu mendapat gratis ongkir.</p>
                <?php endif; ?>

                <a href="checkout.php" class="block text-center mt-5 bg-lumut-800 hover:bg-lumut-700 text-white font-bold py-3 rounded-full">Lanjut ke pemesanan</a>
                <a href="katalog.php" class="block text-center mt-3 text-sm font-semibold text-lumut-700 hover:underline">Lanjut belanja</a>
            </aside>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
