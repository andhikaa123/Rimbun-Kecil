// Menu seluler
const tombolMenu = document.getElementById('tombolMenu');
const menuSeluler = document.getElementById('menuSeluler');
if (tombolMenu && menuSeluler) {
    tombolMenu.addEventListener('click', () => {
        const terbuka = menuSeluler.classList.toggle('hidden') === false;
        tombolMenu.setAttribute('aria-expanded', terbuka);
    });
}

// Tombol tutup pesan, dan pesan hilang sendiri setelah 4 detik
document.querySelectorAll('[data-tutup]').forEach(tombol => {
    tombol.addEventListener('click', () => document.getElementById(tombol.dataset.tutup)?.remove());
});
const notif = document.getElementById('notifikasi');
if (notif) setTimeout(() => notif.remove(), 4000);

// Tombol + dan - pada input jumlah
document.querySelectorAll('[data-jumlah]').forEach(kotak => {
    const input = kotak.querySelector('input[type=number]');
    kotak.querySelectorAll('button[data-langkah]').forEach(tombol => {
        tombol.addEventListener('click', () => {
            const min = parseInt(input.min || '1', 10);
            const max = parseInt(input.max || '999', 10);
            const nilai = (parseInt(input.value, 10) || min) + parseInt(tombol.dataset.langkah, 10);
            input.value = Math.min(max, Math.max(min, nilai));
            if (kotak.dataset.jumlah === 'otomatis') input.form.submit();
        });
    });
});
