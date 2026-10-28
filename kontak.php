<?php
// ============================================
// KONTAK: info toko + formulir pesan
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$judulHalaman = "Kontak";
$halamanAktif = "kontak.php";

$galat = [];
$input = ["nama" => "", "email" => "", "pesan" => ""];
$terkirim = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($input as $k => $_) {
        $input[$k] = trim($_POST[$k] ?? "");
    }
    if (mb_strlen($input["nama"]) < 3) {
        $galat["nama"] = "Nama minimal 3 huruf.";
    }
    if (!filter_var($input["email"], FILTER_VALIDATE_EMAIL)) {
        $galat["email"] = "Format email belum benar.";
    }
    if (mb_strlen($input["pesan"]) < 10) {
        $galat["pesan"] = "Pesan minimal 10 karakter.";
    }
    if (!$galat) {
        // Di versi lanjutan, pesan ini disimpan ke database atau dikirim lewat email.
        $terkirim = true;
        $input = ["nama" => "", "email" => "", "pesan" => ""];
    }
}
$kelasInput = fn(string $f) => "w-full border rounded-xl px-4 py-2 bg-white " . (isset($galat[$f]) ? "border-red-500" : "border-lumut-200");

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-extrabold text-4xl mb-1">Hubungi kami</h1>
    <p class="text-lumut-700 mb-8">Tanya stok, minta saran tanaman, atau mampir langsung ke kebun.</p>

    <div class="grid md:grid-cols-2 gap-8">
        <div class="space-y-4">
            <div class="bg-white border border-lumut-100 rounded-2xl p-6 text-sm space-y-3">
                <p><span class="font-bold block">Alamat kebun</span><?= e($kontak["alamat"]) ?></p>
                <p><span class="font-bold block">Jam buka</span><?= e($kontak["jam"]) ?></p>
                <p><span class="font-bold block">Email</span><?= e($kontak["email"]) ?></p>
                <p><span class="font-bold block">Instagram</span><?= e($kontak["instagram"]) ?></p>
            </div>
            <a href="<?= e(linkWhatsapp($kontak)) ?>" target="_blank" rel="noopener" class="block text-center bg-canang-400 hover:bg-canang-300 text-lumut-900 font-bold py-3 rounded-full">Chat WhatsApp <?= e($kontak["tampilWa"]) ?></a>
        </div>

        <form method="post" class="bg-white border border-lumut-100 rounded-2xl p-6 space-y-4" novalidate>
            <?php if ($terkirim): ?>
                <p class="bg-lumut-100 text-lumut-800 rounded-xl px-4 py-3 text-sm font-semibold" role="status">Pesanmu sudah kami terima. Kami balas maksimal 1x24 jam.</p>
            <?php endif; ?>
            <div>
                <label for="nama" class="block text-sm font-semibold mb-1">Nama</label>
                <input id="nama" name="nama" value="<?= e($input['nama']) ?>" class="<?= $kelasInput('nama') ?>">
                <?php if (isset($galat["nama"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["nama"]) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Email</label>
                <input id="email" name="email" type="email" value="<?= e($input['email']) ?>" class="<?= $kelasInput('email') ?>">
                <?php if (isset($galat["email"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["email"]) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="pesan" class="block text-sm font-semibold mb-1">Pesan</label>
                <textarea id="pesan" name="pesan" rows="4" class="<?= $kelasInput('pesan') ?>"><?= e($input["pesan"]) ?></textarea>
                <?php if (isset($galat["pesan"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["pesan"]) ?></p><?php endif; ?>
            </div>
            <button type="submit" class="bg-lumut-800 hover:bg-lumut-700 text-white font-bold px-6 py-3 rounded-full">Kirim pesan</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
