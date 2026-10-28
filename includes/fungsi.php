<?php
// ============================================
// KUMPULAN FUNGSI TOKO
// ============================================

// ---------- Utilitas tampilan ----------

// Mengamankan teks sebelum ditampilkan di HTML (mencegah XSS)
function e(?string $teks): string
{
    return htmlspecialchars($teks ?? '', ENT_QUOTES, 'UTF-8');
}

// Mengubah angka menjadi format Rupiah, contoh: 65000 -> "Rp 65.000"
function formatRupiah(float $angka): string
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Menghitung harga setelah dipotong diskon (persen)
function hargaSetelahDiskon(int $harga, int $persenDiskon = 0): int
{
    $potongan = $harga * $persenDiskon / 100;
    return (int) round($harga - $potongan);
}

// Menentukan label stok
function labelStok(int $stok): string
{
    return match (true) {
        $stok === 0 => "Habis",
        $stok < 5   => "Sisa $stok",
        default     => "Tersedia",
    };
}

// Menentukan warna badge Tailwind sesuai stok
function warnaStok(int $stok): string
{
    return match (true) {
        $stok === 0 => "bg-red-100 text-red-700",
        $stok < 5   => "bg-amber-100 text-amber-800",
        default     => "bg-lumut-100 text-lumut-700",
    };
}

// Warna badge tingkat kesulitan perawatan
function warnaTingkat(string $tingkat): string
{
    return match ($tingkat) {
        "Mudah"  => "bg-lumut-100 text-lumut-700",
        "Sedang" => "bg-amber-100 text-amber-800",
        "Sulit"  => "bg-rose-100 text-rose-700",
        default  => "bg-stone-100 text-stone-600",
    };
}

// Warna badge status pesanan
function warnaStatus(string $status): string
{
    return match ($status) {
        "baru"     => "bg-amber-100 text-amber-800",
        "diproses" => "bg-sky-100 text-sky-800",
        "dikirim"  => "bg-indigo-100 text-indigo-800",
        "selesai"  => "bg-lumut-100 text-lumut-700",
        default    => "bg-stone-100 text-stone-600",
    };
}

// ---------- Ringkasan & pencarian produk ----------

function hitungNilaiStok(array $daftarProduk): int
{
    $total = 0;
    foreach ($daftarProduk as $item) {
        $total += hargaSetelahDiskon($item["harga"], $item["diskon"]) * $item["stok"];
    }
    return $total;
}

function hitungProdukDiskon(array $daftarProduk): int
{
    $jumlah = 0;
    foreach ($daftarProduk as $item) {
        if ($item["diskon"] > 0) {
            $jumlah++;
        }
    }
    return $jumlah;
}

// Menghitung jumlah produk per kategori
function hitungPerKategori(array $daftarProduk, string $kategori): int
{
    return count(array_filter($daftarProduk, fn($p) => $p["kategori"] === $kategori));
}

// Mencari produk dengan harga akhir (setelah diskon) paling murah.
// Pola: anggap yang pertama terkecil, lalu bandingkan dengan sisanya.
function cariProdukTermurah(array $daftarProduk): array
{
    if (count($daftarProduk) === 0) {
        return [];
    }

    $kodeTermurah  = null;
    $hargaTermurah = PHP_INT_MAX;

    foreach ($daftarProduk as $kode => $item) {
        $harga = hargaSetelahDiskon($item["harga"], $item["diskon"]);
        if ($harga < $hargaTermurah) {
            $hargaTermurah = $harga;
            $kodeTermurah  = $kode;
        }
    }
    return ["kode" => $kodeTermurah, "nama" => $daftarProduk[$kodeTermurah]["nama"], "harga" => $hargaTermurah];
}

// Rata-rata harga normal (sebelum diskon) seluruh produk
function hitungRataRataHarga(array $daftarProduk): int
{
    $semuaHarga = array_column($daftarProduk, "harga");
    if (count($semuaHarga) === 0) {
        return 0;
    }
    return (int) round(array_sum($semuaHarga) / count($semuaHarga));
}

// Banyak produk per kategori, contoh: ["Daun Hias" => 4, "Bunga" => 2]
function hitungJumlahPerKategori(array $daftarProduk): array
{
    $jumlahPerKategori = [];
    foreach ($daftarProduk as $item) {
        // ?? 0 supaya kunci yang belum ada tidak menimbulkan peringatan
        $jumlahPerKategori[$item["kategori"]] = ($jumlahPerKategori[$item["kategori"]] ?? 0) + 1;
    }
    return $jumlahPerKategori;
}

// Menyaring & mengurutkan produk untuk halaman katalog
function cariProduk(array $daftarProduk, string $kategori = "", string $kata = "", string $urut = ""): array
{
    $hasil = array_filter($daftarProduk, function ($p) use ($kategori, $kata) {
        if ($kategori !== "" && $p["kategori"] !== $kategori) {
            return false;
        }
        if ($kata !== "" && stripos($p["nama"] . " " . $p["deskripsi"], $kata) === false) {
            return false;
        }
        return true;
    });

    $harga = fn($p) => hargaSetelahDiskon($p["harga"], $p["diskon"]);

    switch ($urut) {
        case "termurah": uasort($hasil, fn($a, $b) => $harga($a) <=> $harga($b)); break;
        case "termahal": uasort($hasil, fn($a, $b) => $harga($b) <=> $harga($a)); break;
        case "diskon":   uasort($hasil, fn($a, $b) => $b["diskon"] <=> $a["diskon"]); break;
    }
    return $hasil;
}

// ---------- Diskon & ongkir ----------

// Diskon tambahan berdasarkan total belanja
function diskonBelanja(int $totalBelanja): int
{
    $persen = match (true) {
        $totalBelanja >= 500000 => 15,
        $totalBelanja >= 200000 => 10,
        default                 => 0,
    };
    return (int) round($totalBelanja * $persen / 100);
}

// Ongkir: gratis jika belanja (setelah diskon) mencapai batas
function hitungOngkir(int $totalSetelahDiskon, string $kota, array $zonaKirim, int $batasGratis): int
{
    if ($totalSetelahDiskon <= 0 || !isset($zonaKirim[$kota])) {
        return 0;
    }
    return $totalSetelahDiskon >= $batasGratis ? 0 : $zonaKirim[$kota];
}

// ---------- Keranjang belanja (disimpan di session) ----------

function keranjangAmbil(): array
{
    return $_SESSION["keranjang"] ?? [];
}

function keranjangJumlah(): int
{
    return array_sum(keranjangAmbil());
}

function keranjangTambah(string $kode, int $jumlah, array $produk): string
{
    if (!isset($produk[$kode])) {
        return "Produk tidak ditemukan.";
    }
    $stok = $produk[$kode]["stok"];
    if ($stok === 0) {
        return "Maaf, " . $produk[$kode]["nama"] . " sedang habis.";
    }
    $sekarang = $_SESSION["keranjang"][$kode] ?? 0;
    $baru     = min($stok, $sekarang + max(1, $jumlah));
    $_SESSION["keranjang"][$kode] = $baru;

    return $baru < $sekarang + $jumlah
        ? "Jumlah disesuaikan dengan stok yang tersedia ($stok)."
        : $produk[$kode]["nama"] . " masuk keranjang.";
}

function keranjangUbah(string $kode, int $jumlah, array $produk): void
{
    if (!isset($produk[$kode])) {
        return;
    }
    if ($jumlah <= 0) {
        unset($_SESSION["keranjang"][$kode]);
        return;
    }
    $_SESSION["keranjang"][$kode] = min($jumlah, $produk[$kode]["stok"]);
}

function keranjangHapus(string $kode): void
{
    unset($_SESSION["keranjang"][$kode]);
}

function keranjangKosongkan(): void
{
    $_SESSION["keranjang"] = [];
}

// Menghitung ringkasan biaya dari isi keranjang
function hitungRingkasan(array $keranjang, array $produk, string $kota, array $zonaKirim, int $batasGratis): array
{
    $subtotal = 0;
    $jumlahBarang = 0;
    foreach ($keranjang as $kode => $jumlah) {
        if (!isset($produk[$kode])) {
            continue;
        }
        $subtotal    += hargaSetelahDiskon($produk[$kode]["harga"], $produk[$kode]["diskon"]) * $jumlah;
        $jumlahBarang += $jumlah;
    }
    $potongan = diskonBelanja($subtotal);
    $setelah  = $subtotal - $potongan;
    $ongkir   = hitungOngkir($setelah, $kota, $zonaKirim, $batasGratis);

    return [
        "jumlahBarang" => $jumlahBarang,
        "subtotal"     => $subtotal,
        "potongan"     => $potongan,
        "ongkir"       => $ongkir,
        "total"        => $setelah + $ongkir,
    ];
}

// ---------- Pesan notifikasi (flash) & pengalihan ----------

function setFlash(string $pesan, string $jenis = "sukses"): void
{
    $_SESSION["flash"] = ["pesan" => $pesan, "jenis" => $jenis];
}

function ambilFlash(): ?array
{
    $flash = $_SESSION["flash"] ?? null;
    unset($_SESSION["flash"]);
    return $flash;
}

// Hanya mengizinkan pengalihan ke file .php di folder yang sama
function alamatAman(string $alamat, string $cadangan = "katalog.php"): string
{
    return preg_match('/^[a-z_]+\.php(\?[\w=&%.\-]*)?$/i', $alamat) ? $alamat : $cadangan;
}

function alihkan(string $alamat): never
{
    header("Location: " . $alamat);
    exit;
}

// Link WhatsApp toko dengan pesan awal
function linkWhatsapp(array $kontak, string $pesan = "Halo Rimbun Kecil, saya ingin bertanya."): string
{
    return "https://wa.me/" . $kontak["whatsapp"] . "?text=" . rawurlencode($pesan);
}

// Fungsi login/daftar/CSRF
require_once __DIR__ . '/auth.php';
