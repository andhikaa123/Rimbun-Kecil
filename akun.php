<?php
// ============================================
// AKUN SAYA: profil dan riwayat pesanan
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

wajibLogin("akun.php");
$pengguna = penggunaAktif();
$riwayat  = riwayatPesanan($pengguna["email"]);

$judulHalaman = "Akun Saya";
$halamanAktif = "akun.php";
require __DIR__ . '/includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-display font-extrabold text-4xl">Halo, <?= e($pengguna["nama"]) ?></h1>
            <p class="text-lumut-700"><?= e($pengguna["email"]) ?></p>
        </div>
        <form action="logout.php" method="post">
            <?= csrfField() ?>
            <button type="submit" class="border border-lumut-200 bg-white hover:bg-lumut-100 font-semibold px-5 py-2 rounded-full">Keluar</button>
        </form>
    </div>

    <h2 class="font-display font-bold text-2xl mb-4">Riwayat pesanan</h2>

    <?php if (!$riwayat): ?>
        <div class="bg-white border border-lumut-100 rounded-2xl p-10 text-center">
            <p class="text-5xl mb-3">🧾</p>
            <p class="font-display font-bold text-xl">Belum ada pesanan</p>
            <p class="text-lumut-700 mt-1">Pesanan yang kamu buat akan tercatat di sini.</p>
            <a href="katalog.php" class="inline-block mt-5 bg-lumut-800 text-white font-bold px-6 py-3 rounded-full">Mulai belanja</a>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($riwayat as $p): ?>
                <article class="bg-white border border-lumut-100 rounded-2xl p-5">
                    <div class="flex flex-wrap justify-between gap-2 mb-2">
                        <p class="font-bold"><?= e($p["nomor"]) ?></p>
                        <p class="text-sm text-lumut-700"><?= e($p["tanggal"]) ?></p>
                    </div>
                    <p class="text-sm text-lumut-800">
                        <?= e(implode(", ", array_map(fn($b) => $b["nama"] . " ×" . $b["jumlah"], $p["baris"]))) ?>
                    </p>
                    <p class="mt-2 font-bold text-lumut-700">Total <?= formatRupiah($p["ringkasan"]["total"]) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
