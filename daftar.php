<?php
// ============================================
// DAFTAR AKUN BARU
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$kembali = alamatAman($_POST["kembali"] ?? $_GET["kembali"] ?? "", "akun.php");
if (penggunaAktif()) {
    alihkan($kembali);
}

$galat = [];
$input = ["nama" => "", "email" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input["nama"]  = trim($_POST["nama"] ?? "");
    $input["email"] = trim($_POST["email"] ?? "");
    $sandi          = $_POST["sandi"] ?? "";
    $ulang          = $_POST["ulang"] ?? "";

    if (!csrfValid()) {
        $galat["umum"] = "Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.";
    }
    if (mb_strlen($input["nama"]) < 3) {
        $galat["nama"] = "Nama minimal 3 huruf.";
    }
    if (!filter_var($input["email"], FILTER_VALIDATE_EMAIL)) {
        $galat["email"] = "Format email belum benar.";
    }
    if (strlen($sandi) < 8) {
        $galat["sandi"] = "Kata sandi minimal 8 karakter.";
    }
    if ($sandi !== $ulang) {
        $galat["ulang"] = "Pengulangan kata sandi tidak sama.";
    }

    if (!$galat) {
        if (!daftarPengguna($input["nama"], $input["email"], $sandi)) {
            $galat["email"] = "Email ini sudah terdaftar. Silakan masuk.";
        } else {
            masuk($input["email"], $sandi);
            setFlash("Akun berhasil dibuat. Selamat datang, " . $input["nama"] . "!");
            alihkan($kembali);
        }
    }
}

$kelasInput = fn(string $f) => "w-full border rounded-xl px-4 py-2 " . (isset($galat[$f]) ? "border-red-500" : "border-lumut-200");

$judulHalaman = "Daftar";
$halamanAktif = "daftar.php";
require __DIR__ . '/includes/header.php';
?>

<div class="max-w-md mx-auto px-4 py-14">
    <h1 class="font-display font-extrabold text-4xl mb-1">Buat akun</h1>
    <p class="text-lumut-700 mb-6">Cukup satu kali, lalu pesananmu tersimpan di akun.</p>

    <form method="post" class="bg-white border border-lumut-100 rounded-2xl p-6 space-y-4" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="kembali" value="<?= e($kembali) ?>">
        <?php if (isset($galat["umum"])): ?>
            <p class="bg-red-100 text-red-800 rounded-xl px-4 py-3 text-sm font-semibold" role="alert"><?= e($galat["umum"]) ?></p>
        <?php endif; ?>
        <div>
            <label for="nama" class="block text-sm font-semibold mb-1">Nama lengkap</label>
            <input id="nama" name="nama" value="<?= e($input['nama']) ?>" autocomplete="name" class="<?= $kelasInput('nama') ?>">
            <?php if (isset($galat["nama"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["nama"]) ?></p><?php endif; ?>
        </div>
        <div>
            <label for="email" class="block text-sm font-semibold mb-1">Email</label>
            <input id="email" name="email" type="email" value="<?= e($input['email']) ?>" autocomplete="email" class="<?= $kelasInput('email') ?>">
            <?php if (isset($galat["email"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["email"]) ?></p><?php endif; ?>
        </div>
        <div>
            <label for="sandi" class="block text-sm font-semibold mb-1">Kata sandi</label>
            <input id="sandi" name="sandi" type="password" autocomplete="new-password" class="<?= $kelasInput('sandi') ?>">
            <?php if (isset($galat["sandi"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["sandi"]) ?></p><?php endif; ?>
        </div>
        <div>
            <label for="ulang" class="block text-sm font-semibold mb-1">Ulangi kata sandi</label>
            <input id="ulang" name="ulang" type="password" autocomplete="new-password" class="<?= $kelasInput('ulang') ?>">
            <?php if (isset($galat["ulang"])): ?><p class="text-red-700 text-sm mt-1"><?= e($galat["ulang"]) ?></p><?php endif; ?>
        </div>
        <button type="submit" class="w-full bg-lumut-800 hover:bg-lumut-700 text-white font-bold py-3 rounded-full">Daftar</button>
    </form>
    <p class="text-sm text-center mt-5">Sudah punya akun? <a href="login.php?kembali=<?= urlencode($kembali) ?>" class="font-bold underline">Masuk</a></p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
