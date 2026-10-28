<?php
// ============================================
// MASUK
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

$kembali = alamatAman($_POST["kembali"] ?? $_GET["kembali"] ?? "", "akun.php");
if (penggunaAktif()) {
    alihkan($kembali);
}

$galat = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $sandi = $_POST["sandi"] ?? "";

    if (!csrfValid()) {
        $galat = "Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.";
    } elseif ($email === "" || $sandi === "") {
        $galat = "Isi email dan kata sandi.";
    } elseif (!masuk($email, $sandi)) {
        // Pesan sengaja dibuat umum agar tidak membocorkan email mana yang terdaftar
        $galat = "Email atau kata sandi salah.";
    } else {
        setFlash("Selamat datang kembali, " . penggunaAktif()["nama"] . "!");
        alihkan($kembali);
    }
}

$judulHalaman = "Masuk";
$halamanAktif = "login.php";
require __DIR__ . '/includes/header.php';
?>

<div class="max-w-md mx-auto px-4 py-14">
    <h1 class="font-display font-extrabold text-4xl mb-1">Masuk</h1>
    <p class="text-lumut-700 mb-6">Masuk untuk memesan dan melihat riwayat belanjamu.</p>

    <form method="post" class="bg-white border border-lumut-100 rounded-2xl p-6 space-y-4" novalidate>
        <?= csrfField() ?>
        <input type="hidden" name="kembali" value="<?= e($kembali) ?>">
        <?php if ($galat): ?>
            <p class="bg-red-100 text-red-800 rounded-xl px-4 py-3 text-sm font-semibold" role="alert"><?= e($galat) ?></p>
        <?php endif; ?>
        <div>
            <label for="email" class="block text-sm font-semibold mb-1">Email</label>
            <input id="email" name="email" type="email" value="<?= e($email) ?>" autocomplete="email" class="w-full border border-lumut-200 rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label for="sandi" class="block text-sm font-semibold mb-1">Kata sandi</label>
            <input id="sandi" name="sandi" type="password" autocomplete="current-password" class="w-full border border-lumut-200 rounded-xl px-4 py-2" required>
        </div>
        <button type="submit" class="w-full bg-lumut-800 hover:bg-lumut-700 text-white font-bold py-3 rounded-full">Masuk</button>
    </form>
    <p class="text-sm text-center mt-5">Belum punya akun? <a href="daftar.php?kembali=<?= urlencode($kembali) ?>" class="font-bold underline">Daftar gratis</a></p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
