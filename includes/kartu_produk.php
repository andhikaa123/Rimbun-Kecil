<?php
// Partial: kartu satu produk. Butuh variabel $kode dan $item dari halaman pemanggil.
$hargaAkhir = hargaSetelahDiskon($item["harga"], $item["diskon"]);
$habis = $item["stok"] === 0;
?>
<article class="bg-white rounded-2xl border border-lumut-100 overflow-hidden flex flex-col hover:border-lumut-600 transition-colors">
    <a href="produk.php?kode=<?= urlencode($kode) ?>" class="block bg-lumut-50 text-6xl text-center py-10 relative <?= $habis ? 'opacity-60' : '' ?>" aria-label="Lihat <?= e($item['nama']) ?>">
        <span aria-hidden="true"><?= $item["ikon"] ?></span>
        <?php if ($item["diskon"] > 0): ?>
            <span class="absolute top-3 left-3 bg-canang-400 text-lumut-900 text-xs font-bold px-2 py-1 rounded-full">-<?= $item["diskon"] ?>%</span>
        <?php endif; ?>
    </a>
    <div class="p-4 flex flex-col flex-1">
        <p class="text-xs text-lumut-600 font-semibold"><?= e($item["kategori"]) ?></p>
        <h3 class="font-display font-bold text-lg leading-snug mb-2">
            <a href="produk.php?kode=<?= urlencode($kode) ?>" class="hover:underline"><?= e($item["nama"]) ?></a>
        </h3>

        <?php if ($item["diskon"] > 0): ?>
            <p class="text-sm text-stone-400 line-through"><?= formatRupiah($item["harga"]) ?></p>
        <?php endif; ?>
        <p class="text-xl font-bold text-lumut-700 mb-3"><?= formatRupiah($hargaAkhir) ?></p>

        <div class="mt-auto flex items-center justify-between gap-2">
            <span class="text-xs font-semibold px-2 py-1 rounded-full <?= warnaStok($item["stok"]) ?>"><?= labelStok($item["stok"]) ?></span>
            <form action="keranjang.php" method="post">
                <input type="hidden" name="aksi" value="tambah">
                <input type="hidden" name="kode" value="<?= e($kode) ?>">
                <input type="hidden" name="kembali" value="<?= e(basename($_SERVER['SCRIPT_NAME']) . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>">
                <button type="submit" <?= $habis ? 'disabled' : '' ?>
                    class="text-sm font-bold px-3 py-2 rounded-full <?= $habis ? 'bg-stone-200 text-stone-500 cursor-not-allowed' : 'bg-lumut-800 text-white hover:bg-lumut-700' ?>">
                    <?= $habis ? 'Habis' : '+ Keranjang' ?>
                </button>
            </form>
        </div>
    </div>
</article>
