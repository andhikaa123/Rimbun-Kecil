<?php
// ============================================
// NOTA PESANAN TERAKHIR
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$judulHalaman = "Nota Pesanan";
$halamanAktif = "pesanan.php";

$nota = $_SESSION["pesanan_terakhir"] ?? null;

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 py-10">
<?php if (!$nota): ?>
    <div class="bg-white border border-lumut-100 rounded-2xl p-12 text-center">
        <p class="text-6xl mb-3">🧾</p>
        <p class="font-display font-bold text-xl">Belum ada pesanan</p>
        <p class="text-lumut-700 mt-1">Nota akan muncul di sini setelah kamu menyelesaikan pemesanan.</p>
        <a href="katalog.php" class="inline-block mt-5 bg-lumut-800 text-white font-bold px-6 py-3 rounded-full">Mulai belanja</a>
    </div>
<?php else: ?>
    <?php
    $p = $nota["pelanggan"];
    $r = $nota["ringkasan"];
    $pesanWa = "Halo Rimbun Kecil, saya konfirmasi pesanan {$nota['nomor']} atas nama {$p['nama']}.";
    ?>
    <h1 class="font-display font-extrabold text-4xl mb-1">Pesanan diterima</h1>
    <p class="text-lumut-700 mb-6">Simpan nomor pesananmu: <strong><?= e($nota["nomor"]) ?></strong></p>

    <div class="bg-white border border-lumut-100 rounded-2xl p-6">
        <div class="flex flex-wrap justify-between gap-4 text-sm mb-6">
            <div>
                <p class="text-lumut-600 font-semibold">Dikirim ke</p>
                <p class="font-bold"><?= e($p["nama"]) ?> &middot; <?= e($p["telepon"]) ?></p>
                <p class="max-w-xs"><?= e($p["alamat"]) ?>, <?= e($p["kota"]) ?></p>
            </div>
            <div class="sm:text-right">
                <p class="text-lumut-600 font-semibold">Tanggal</p>
                <p class="font-bold"><?= e($nota["tanggal"]) ?></p>
                <p class="text-lumut-600 font-semibold mt-2">Pembayaran</p>
                <p class="font-bold"><?= e($metodeBayar[$p["metode"]] ?? $p["metode"]) ?></p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-lumut-100 text-lumut-600">
                        <th class="py-2">No</th>
                        <th>Produk</th>
                        <th class="text-right">Harga</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nota["baris"] as $i => $b): ?>
                        <tr class="border-b border-lumut-100">
                            <td class="py-2"><?= $i + 1 ?></td>
                            <td><?= $b["ikon"] ?> <?= e($b["nama"]) ?></td>
                            <td class="text-right"><?= formatRupiah($b["harga"]) ?></td>
                            <td class="text-center"><?= $b["jumlah"] ?></td>
                            <td class="text-right"><?= formatRupiah($b["harga"] * $b["jumlah"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 space-y-1 text-sm">
            <p class="flex justify-between"><span>Total barang</span><span><?= $r["jumlahBarang"] ?> item</span></p>
            <p class="flex justify-between"><span>Subtotal</span><span><?= formatRupiah($r["subtotal"]) ?></span></p>
            <p class="flex justify-between text-lumut-700"><span>Diskon belanja</span><span>- <?= formatRupiah($r["potongan"]) ?></span></p>
            <p class="flex justify-between"><span>Ongkir (<?= e($p["kota"]) ?>)</span><span><?= $r["ongkir"] === 0 ? "Gratis" : formatRupiah($r["ongkir"]) ?></span></p>
            <p class="flex justify-between text-xl font-bold border-t border-lumut-100 pt-2"><span>Total bayar</span><span><?= formatRupiah($r["total"]) ?></span></p>
        </div>

        <?php if ($p["catatan"] !== ""): ?>
            <p class="mt-4 text-sm bg-lumut-50 rounded-xl p-3">Catatan: <?= e($p["catatan"]) ?></p>
        <?php endif; ?>
    </div>

    <div class="flex flex-wrap gap-3 mt-6">
        <a href="<?= e(linkWhatsapp($kontak, $pesanWa)) ?>" target="_blank" rel="noopener" class="bg-canang-400 hover:bg-canang-300 text-lumut-900 font-bold px-6 py-3 rounded-full">Konfirmasi lewat WhatsApp</a>
        <button type="button" onclick="window.print()" class="border border-lumut-200 bg-white font-semibold px-6 py-3 rounded-full hover:bg-lumut-100">Cetak nota</button>
        <a href="akun.php" class="px-6 py-3 font-semibold text-lumut-700 hover:underline">Riwayat pesanan</a>
        <a href="katalog.php" class="px-6 py-3 font-semibold text-lumut-700 hover:underline">Belanja lagi</a>
    </div>
<?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
