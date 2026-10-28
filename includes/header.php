<?php
// Variabel $judulHalaman dan $halamanAktif diisi oleh halaman yang memanggil header ini.
$judulHalaman = $judulHalaman ?? "Beranda";
$halamanAktif = $halamanAktif ?? "";
$deskripsiHalaman = $deskripsiHalaman ?? "Tanaman hias sehat dan siap kirim di seluruh Bali. Dari monstera sampai anggrek, lengkap dengan panduan perawatan.";

// Daftar menu navigasi: nama file => teks menu
$menu = [
    "index.php"     => "Beranda",
    "katalog.php"   => "Katalog",
    "perawatan.php" => "Tips Perawatan",
    "tentang.php"   => "Tentang",
    "kontak.php"    => "Kontak",
];
$jumlahKeranjang = keranjangJumlah();
$flash = ambilFlash();
$pengguna = penggunaAktif();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($deskripsiHalaman) ?>">
    <title><?= e($judulHalaman) ?> - <?= e($namaToko) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-display: "Bricolage Grotesque", ui-sans-serif, sans-serif;
            --font-sans: "Figtree", ui-sans-serif, system-ui, sans-serif;
            --color-lumut-50:  #f1f6ee;
            --color-lumut-100: #dfeadb;
            --color-lumut-200: #c2d6bb;
            --color-lumut-600: #2f6b45;
            --color-lumut-700: #25583a;
            --color-lumut-800: #1c4430;
            --color-lumut-900: #12301f;
            --color-canang-300: #f7d56b;
            --color-canang-400: #f2bd36;
            --color-canang-500: #e3a312;
            --color-pucuk: #eef3ec;
        }
    </style>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-pucuk text-lumut-900 min-h-screen flex flex-col font-sans antialiased">

    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-white focus:px-4 focus:py-2">Lompat ke konten</a>

    <header class="bg-white/90 backdrop-blur border-b border-lumut-100 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="index.php" class="flex items-center gap-2 group">
                <span class="text-2xl" aria-hidden="true">🪴</span>
                <span class="font-display font-extrabold text-xl tracking-tight text-lumut-800"><?= e($namaToko) ?></span>
            </a>

            <nav class="hidden md:flex items-center gap-1 text-sm font-semibold" aria-label="Menu utama">
                <?php foreach ($menu as $file => $teks): ?>
                    <?php $kelas = ($file === $halamanAktif) ? "bg-lumut-800 text-white" : "text-lumut-700 hover:bg-lumut-100"; ?>
                    <a href="<?= $file ?>" class="px-3 py-2 rounded-full <?= $kelas ?>"><?= e($teks) ?></a>
                <?php endforeach; ?>
            </nav>

            <div class="flex items-center gap-2">
                <?php if ($pengguna): ?>
                    <a href="akun.php" class="hidden sm:inline text-sm font-semibold px-3 py-2 rounded-full <?= $halamanAktif === 'akun.php' ? 'bg-lumut-800 text-white' : 'text-lumut-700 hover:bg-lumut-100' ?>">Halo, <?= e(explode(' ', $pengguna['nama'])[0]) ?></a>
                    <form action="logout.php" method="post" class="hidden sm:block">
                        <?= csrfField() ?>
                        <button type="submit" class="text-sm font-semibold px-3 py-2 rounded-full text-lumut-700 hover:bg-lumut-100">Keluar</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="hidden sm:inline text-sm font-semibold px-3 py-2 rounded-full <?= in_array($halamanAktif, ['login.php', 'daftar.php']) ? 'bg-lumut-800 text-white' : 'text-lumut-700 hover:bg-lumut-100' ?>">Masuk</a>
                <?php endif; ?>
                <a href="keranjang.php" class="relative flex items-center gap-2 bg-canang-400 hover:bg-canang-300 text-lumut-900 font-bold text-sm px-4 py-2 rounded-full" aria-label="Keranjang, <?= $jumlahKeranjang ?> barang">
                    <span aria-hidden="true">🧺</span> Keranjang
                    <?php if ($jumlahKeranjang > 0): ?>
                        <span class="bg-lumut-900 text-white text-xs rounded-full min-w-5 h-5 px-1 grid place-items-center"><?= $jumlahKeranjang ?></span>
                    <?php endif; ?>
                </a>
                <button id="tombolMenu" class="md:hidden p-2 rounded-lg hover:bg-lumut-100" aria-label="Buka menu" aria-expanded="false" aria-controls="menuSeluler">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
        <nav id="menuSeluler" class="hidden md:hidden border-t border-lumut-100 px-4 py-3 flex flex-col gap-1 text-sm font-semibold" aria-label="Menu seluler">
            <?php foreach (array_merge($menu, ["pesanan.php" => "Nota Pesanan"]) as $file => $teks): ?>
                <?php $kelas = ($file === $halamanAktif) ? "bg-lumut-800 text-white" : "text-lumut-700 hover:bg-lumut-100"; ?>
                <a href="<?= $file ?>" class="px-3 py-2 rounded-lg <?= $kelas ?>"><?= e($teks) ?></a>
            <?php endforeach; ?>
            <?php if ($pengguna): ?>
                <a href="akun.php" class="px-3 py-2 rounded-lg text-lumut-700 hover:bg-lumut-100">Akun saya (<?= e($pengguna['nama']) ?>)</a>
                <form action="logout.php" method="post">
                    <?= csrfField() ?>
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-lumut-700 hover:bg-lumut-100">Keluar</button>
                </form>
            <?php else: ?>
                <a href="login.php" class="px-3 py-2 rounded-lg text-lumut-700 hover:bg-lumut-100">Masuk</a>
                <a href="daftar.php" class="px-3 py-2 rounded-lg text-lumut-700 hover:bg-lumut-100">Daftar</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php if ($flash): ?>
        <div id="notifikasi" role="status" class="max-w-6xl mx-auto w-full px-4 mt-4">
            <div class="rounded-xl px-4 py-3 text-sm font-semibold flex items-center justify-between gap-3 <?= $flash['jenis'] === 'galat' ? 'bg-red-100 text-red-800' : 'bg-lumut-100 text-lumut-800' ?>">
                <span><?= e($flash["pesan"]) ?></span>
                <button type="button" data-tutup="notifikasi" class="text-lg leading-none" aria-label="Tutup pesan">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <main id="konten" class="flex-1 w-full">
