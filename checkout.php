<?php
// ============================================
// CHECKOUT: isi data pengiriman, validasi, lalu buat nota
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$keranjang = keranjangAmbil();
if (!$keranjang) {
    setFlash("Keranjang masih kosong. Pilih tanaman dulu.", "galat");
    alihkan("katalog.php");
}

wajibLogin("checkout.php");
$pengguna = penggunaAktif();

$galat = [];
$input = ["nama" => $pengguna["nama"], "telepon" => "", "kota" => "Denpasar", "alamat" => "", "metode" => "transfer", "catatan" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($input as $kunci => $_) {
        $input[$kunci] = trim($_POST[$kunci] ?? "");
    }

    // Validasi
    if (mb_strlen($input["nama"]) < 3) {
        $galat["nama"] = "Nama minimal 3 huruf.";
    }
    if (!preg_match('/^(\+62|62|0)8[0-9]{8,12}$/', preg_replace('/[\s-]/', '', $input["telepon"]))) {
        $galat["telepon"] = "Gunakan nomor HP Indonesia, contoh 081234567890.";
    }
    if (!isset($zonaKirim[$input["kota"]])) {
        $galat["kota"] = "Pilih wilayah pengiriman.";
    }
    if (mb_strlen($input["alamat"]) < 10) {
        $galat["alamat"] = "Tulis alamat lengkap (jalan, nomor, kecamatan).";
    }
    if (!isset($metodeBayar[$input["metode"]])) {
        $galat["metode"] = "Pilih metode pembayaran.";
    } elseif ($input["metode"] === "cod" && !in_array($input["kota"], ["Denpasar", "Badung"], true)) {
        $galat["metode"] = "Bayar di tempat hanya untuk Denpasar dan Badung.";
    }

    // Pastikan stok masih cukup
    foreach ($keranjang as $kode => $jumlah) {
        if (!isset($produk[$kode]) || $jumlah > $produk[$kode]["stok"]) {
            $galat["stok"] = "Stok salah satu produk berubah. Periksa kembali keranjangmu.";
        }
    }

    if (!$galat) {
        $ringkasan = hitungRingkasan($keranjang, $produk, $input["kota"], $zonaKirim, $batasGratisOngkir);

        $baris = [];
        foreach ($keranjang as $kode => $jumlah) {
            $baris[] = [
                "kode"   => $kode,
                "nama"   => $produk[$kode]["nama"],
                "ikon"   => $produk[$kode]["ikon"],
                "harga"  => hargaSetelahDiskon($produk[$kode]["harga"], $produk[$kode]["diskon"]),
                "jumlah" => $jumlah,
            ];
        }

        $_SESSION["pesanan_terakhir"] = [
            "nomor"     => "RK-" . date("ymd") . "-" . random_int(1000, 9999),
            "tanggal"   => date("d/m/Y H:i"),
            "pelanggan" => $input,
            "baris"     => $baris,
            "ringkasan" => $ringkasan,
        ];
        simpanPesanan($pengguna["email"], $_SESSION["pesanan_terakhir"]);
        keranjangKosongkan();
        setFlash("Pesanan berhasil dibuat. Terima kasih sudah belanja!");
        alihkan("pesanan.php");
    }
}

$judulHalaman = "Pemesanan";
$halamanAktif = "keranjang.php";
$ringkasan = hitungRingkasan($keranjang, $produk, $input["kota"], $zonaKirim, $batasGratisOngkir);
$setelahDiskon = $ringkasan["subtotal"] - $ringkasan["potongan"];

// Kelas input, merah jika ada galat
$kelasInput = fn(string $f) => "w-full border rounded-xl px-4 py-2 bg-white " . (isset($galat[$f]) ? "border-red-500" : "border-lumut-200");

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-extrabold text-4xl mb-6">Data pemesanan</h1>

    <?php if (isset($galat["stok"])): ?>
        <p class="bg-red-100 text-red-800 rounded-xl px-4 py-3 mb-4 text-sm font-semibold"><?= e($galat["stok"]) ?> <a class="underline" href="keranjang.php">Ke keranjang</a></p>
    <?php endif; ?>

    <form method="post" class="grid lg:grid-cols-[1fr_22rem] gap-8 items-start" novalidate>
        <div class="bg-white border border-lumut-100 rounded-2xl p-6 space-y-5">
            <div>
                <label for="nama" class="block text-sm font-semibold mb-1">Nama penerima</label>
                <input id="nama" name="nama" value="<?= e($input['nama']) ?>" class="<?= $kelasInput('nama') ?>" autocomplete="name">
                <?php if (isset($galat["nama"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["nama"]) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="telepon" class="block text-sm font-semibold mb-1">Nomor HP / WhatsApp</label>
                <input id="telepon" name="telepon" value="<?= e($input['telepon']) ?>" class="<?= $kelasInput('telepon') ?>" inputmode="tel" autocomplete="tel">
                <?php if (isset($galat["telepon"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["telepon"]) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="kota" class="block text-sm font-semibold mb-1">Wilayah pengiriman</label>
                <select id="kota" name="kota" class="<?= $kelasInput('kota') ?>">
                    <?php foreach ($zonaKirim as $nama => $biaya): ?>
                        <option value="<?= e($nama) ?>" <?= $nama === $input["kota"] ? "selected" : "" ?>><?= e($nama) ?> (<?= formatRupiah($biaya) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($galat["kota"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["kota"]) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="alamat" class="block text-sm font-semibold mb-1">Alamat lengkap</label>
                <textarea id="alamat" name="alamat" rows="3" class="<?= $kelasInput('alamat') ?>" autocomplete="street-address"><?= e($input["alamat"]) ?></textarea>
                <?php if (isset($galat["alamat"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["alamat"]) ?></p><?php endif; ?>
            </div>
            <fieldset>
                <legend class="block text-sm font-semibold mb-2">Metode pembayaran</legend>
                <div class="space-y-2">
                    <?php foreach ($metodeBayar as $nilai => $teks): ?>
                        <label class="flex items-center gap-3 border border-lumut-200 rounded-xl px-4 py-3 cursor-pointer has-checked:border-lumut-700 has-checked:bg-lumut-50">
                            <input type="radio" name="metode" value="<?= $nilai ?>" <?= $nilai === $input["metode"] ? "checked" : "" ?>>
                            <span class="text-sm"><?= e($teks) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($galat["metode"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["metode"]) ?></p><?php endif; ?>
            </fieldset>
            <div>
                <label for="catatan" class="block text-sm font-semibold mb-1">Catatan untuk kami (opsional)</label>
                <input id="catatan" name="catatan" value="<?= e($input['catatan']) ?>" class="<?= $kelasInput('catatan') ?>" placeholder="Contoh: tolong kemas sebagai hadiah">
            </div>
        </div>

        <aside class="bg-white border border-lumut-100 rounded-2xl p-6 lg:sticky lg:top-24">
            <h2 class="font-display font-bold text-xl mb-4">Pesananmu</h2>
            <ul class="text-sm space-y-2 mb-4">
                <?php foreach ($keranjang as $kode => $jumlah): ?>
                    <li class="flex justify-between gap-3">
                        <span><?= $produk[$kode]["ikon"] ?> <?= e($produk[$kode]["nama"]) ?> &times; <?= $jumlah ?></span>
                        <span><?= formatRupiah(hargaSetelahDiskon($produk[$kode]["harga"], $produk[$kode]["diskon"]) * $jumlah) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="space-y-2 text-sm border-t border-lumut-100 pt-3">
                <p class="flex justify-between"><span>Subtotal</span><span><?= formatRupiah($ringkasan["subtotal"]) ?></span></p>
                <p class="flex justify-between text-lumut-700"><span>Diskon belanja</span><span>- <?= formatRupiah($ringkasan["potongan"]) ?></span></p>
                <p class="flex justify-between"><span>Ongkir</span><span id="teksOngkir"><?= $ringkasan["ongkir"] === 0 ? "Gratis" : formatRupiah($ringkasan["ongkir"]) ?></span></p>
                <p class="flex justify-between text-lg font-bold border-t border-lumut-100 pt-3"><span>Total bayar</span><span id="teksTotal"><?= formatRupiah($ringkasan["total"]) ?></span></p>
            </div>
            <button type="submit" class="w-full mt-5 bg-lumut-800 hover:bg-lumut-700 text-white font-bold py-3 rounded-full">Buat pesanan</button>
            <a href="keranjang.php" class="block text-center mt-3 text-sm font-semibold text-lumut-700 hover:underline">Ubah keranjang</a>
        </aside>
    </form>
</div>

<script>
// Perbarui ongkir & total langsung saat wilayah diganti (server tetap menghitung ulang saat pesanan dibuat)
const zona = <?= json_encode($zonaKirim) ?>;
const setelahDiskon = <?= $setelahDiskon ?>;
const batasGratis = <?= $batasGratisOngkir ?>;
const rupiah = n => 'Rp ' + n.toLocaleString('id-ID');
document.getElementById('kota').addEventListener('change', e => {
    const ongkir = setelahDiskon >= batasGratis ? 0 : (zona[e.target.value] ?? 0);
    document.getElementById('teksOngkir').textContent = ongkir === 0 ? 'Gratis' : rupiah(ongkir);
    document.getElementById('teksTotal').textContent = rupiah(setelahDiskon + ongkir);
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
