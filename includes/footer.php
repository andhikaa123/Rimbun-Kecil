    </main>

    <footer class="bg-lumut-900 text-lumut-100 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4 text-sm">
            <div>
                <p class="font-display font-extrabold text-xl text-white mb-2">🪴 <?= e($namaToko) ?></p>
                <p class="text-lumut-200"><?= e($tagline) ?>. Tanaman dirawat langsung di kebun kami di Denpasar sebelum dikirim.</p>
            </div>
            <div>
                <p class="font-bold text-white mb-3">Jelajahi</p>
                <ul class="space-y-2">
                    <?php foreach ($menu as $file => $teks): ?>
                        <li><a class="hover:text-canang-300" href="<?= $file ?>"><?= e($teks) ?></a></li>
                    <?php endforeach; ?>
                    <li><a class="hover:text-canang-300" href="keranjang.php">Keranjang</a></li>
                </ul>
            </div>
            <div>
                <p class="font-bold text-white mb-3">Kategori</p>
                <ul class="space-y-2">
                    <?php foreach ($kategori as $nama => $info): ?>
                        <li><a class="hover:text-canang-300" href="katalog.php?kategori=<?= urlencode($nama) ?>"><?= e($nama) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <p class="font-bold text-white mb-3">Kunjungi kami</p>
                <address class="not-italic space-y-2 text-lumut-200">
                    <p><?= e($kontak["alamat"]) ?></p>
                    <p><?= e($kontak["jam"]) ?></p>
                    <p><a class="hover:text-canang-300" href="<?= e(linkWhatsapp($kontak)) ?>" target="_blank" rel="noopener">WhatsApp <?= e($kontak["tampilWa"]) ?></a></p>
                    <p><?= e($kontak["instagram"]) ?></p>
                </address>
            </div>
        </div>
        <div class="border-t border-lumut-800 text-center text-xs text-lumut-200 py-4">
            &copy; <?= date('Y') ?> <?= e($namaToko) ?> &middot; Praktikum Pemrograman Web
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
</body>
</html>
