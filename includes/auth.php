<?php
// ============================================
// AUTENTIKASI & PESANAN (tanpa database)
// Akun dan pesanan disimpan di file JSON di folder data/.
// ============================================

const FILE_PENGGUNA = __DIR__ . '/../data/pengguna.json';
const FILE_PESANAN  = __DIR__ . '/../data/pesanan.json';

function bacaJson(string $file): array
{
    if (!is_file($file)) {
        return [];
    }
    $data = json_decode((string) file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function tulisJson(string $file, array $data): bool
{
    $folder = dirname($file);
    if (!is_dir($folder)) {
        mkdir($folder, 0775, true);
    }
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}

// ---------- CSRF: pastikan form benar-benar dikirim dari situs ini ----------

function csrfToken(): string
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf" value="' . csrfToken() . '">';
}

function csrfValid(): bool
{
    return isset($_POST["csrf"], $_SESSION["csrf"]) && hash_equals($_SESSION["csrf"], $_POST["csrf"]);
}

// ---------- Pengguna ----------

function penggunaAktif(): ?array
{
    $p = $_SESSION["pengguna"] ?? null;
    return (is_array($p) && isset($p["id"])) ? $p : null;
}

// Halaman yang butuh login memanggil fungsi ini di bagian atas
function wajibLogin(string $kembali): void
{
    if (!penggunaAktif()) {
        setFlash("Masuk dulu untuk melanjutkan.", "galat");
        alihkan("login.php?kembali=" . urlencode($kembali));
    }
}

function daftarPengguna(string $nama, string $email, string $sandi): bool
{
    $email    = strtolower($email);
    $pengguna = bacaJson(FILE_PENGGUNA);
    if (isset($pengguna[$email])) {
        return false;
    }
    $pengguna[$email] = [
        "nama"   => $nama,
        "email"  => $email,
        "sandi"  => password_hash($sandi, PASSWORD_DEFAULT),
        "dibuat" => date("d/m/Y H:i"),
    ];
    return tulisJson(FILE_PENGGUNA, $pengguna);
}

function masuk(string $email, string $sandi): bool
{
    $email = strtolower($email);
    $data  = bacaJson(FILE_PENGGUNA)[$email] ?? null;
    if (!$data || !password_verify($sandi, $data["sandi"])) {
        return false;
    }
    session_regenerate_id(true); // cegah session fixation; isi keranjang tetap terbawa
    $_SESSION["pengguna"] = ["id" => $data["email"], "nama" => $data["nama"], "email" => $data["email"]];
    return true;
}

function keluar(): void
{
    unset($_SESSION["pengguna"], $_SESSION["pesanan_terakhir"]);
    session_regenerate_id(true);
}

// ---------- Pesanan ----------

// Menyimpan pesanan ke data/pesanan.json. ($penggunaId = email pengguna)
// Catatan: tanpa database, stok di array tidak berkurang otomatis.
function simpanPesanan(string $penggunaId, array $nota): bool
{
    $semua   = bacaJson(FILE_PESANAN);
    $semua[] = ["pengguna" => strtolower($penggunaId), "status" => "baru"] + $nota;
    return tulisJson(FILE_PESANAN, $semua);
}

function riwayatPesanan(string $penggunaId): array
{
    $milikUser = array_filter(bacaJson(FILE_PESANAN), fn($p) => ($p["pengguna"] ?? "") === strtolower($penggunaId));
    return array_reverse(array_values($milikUser)); // terbaru di atas
}
