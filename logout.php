<?php
// ============================================
// KELUAR (hanya lewat POST + token CSRF)
// ============================================
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/fungsi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && csrfValid()) {
    keluar();
    setFlash("Kamu sudah keluar. Sampai jumpa!");
    alihkan("index.php");
}
alihkan("index.php");
