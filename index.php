<?php
$page_title = "Beranda";
$extra_head = ['<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">'];
$extra_scripts = ['assets/js/app.js'];
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$sedangDipinjam = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();
$transaksiTerbaru = $pdo->query(
    "SELECT a.nama AS nama_anggota, b.judul AS judul_buku, p.tanggal_pinjam, p.status
     FROM peminjaman p
     JOIN anggota a ON a.id = p.anggota_id
     JOIN buku b ON b.id = p.buku_id
     ORDER BY p.tanggal_pinjam DESC, p.id DESC
     LIMIT 5"
)->fetchAll(PDO::FETCH_ASSOC);

// Buku yang stoknya masih tersedia — ditampilkan untuk semua orang (Tamu & Petugas)
$bukuTersedia = $pdo->query(
    "SELECT judul, pengarang, stok FROM buku WHERE stok > 0 ORDER BY judul ASC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);
?>

        <!-- Tampilan khusus Tamu -->
        <section data-role="tamu-only">
            <h2>Selamat Datang, Tamu 👋</h2>
            <p>Anda dapat melihat katalog buku yang tersedia di perpustakaan. Untuk meminjam buku atau mengelola data anggota, silakan hubungi petugas perpustakaan.</p>
            <p class="index-catalog-action">
                <a href="buku/list.php" class="btn-action pinjam index-catalog-link">📖 Lihat Katalog Buku</a>
            </p>
        </section>

        <!-- Tampilan khusus Petugas -->
        <section data-role="petugas-only">
            <h2>Selamat Datang, <span id="namaPetugas">Petugas</span> 👋</h2>
            <p>Kelola data buku, anggota, peminjaman, dan pengembalian perpustakaan dari dashboard ini.</p>
            <div class="quick-actions">
                <a href="transaksi/pinjam.php" class="btn-action pinjam">➕ Peminjaman Baru</a>
                <a href="transaksi/kembalikan.php" class="btn-action kembali">✅ Pengembalian</a>
            </div>
        </section>

        <!-- Buku Tersedia — TIDAK dibatasi data-role, jadi selalu tampil untuk Tamu maupun Petugas -->
        <section>
            <h2>📖 Buku Tersedia</h2>
            <p style="margin-bottom:0.75rem; color:#8a7a6d;">Buku yang masih bisa dipinjam saat ini (update otomatis).</p>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-buku-tersedia">
                        <?php if (empty($bukuTersedia)): ?>
                        <tr>
                            <td colspan="3" style="text-align:center; color:#8a7a6d;">Belum ada buku tersedia saat ini.</td>
                        </tr>
                        <?php else: foreach ($bukuTersedia as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['judul']); ?></td>
                            <td><?php echo htmlspecialchars($b['pengarang']); ?></td>
                            <td><?php echo htmlspecialchars($b['stok']); ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="stats-grid" data-role="petugas-only">
            <h2>Ringkasan</h2>
            <article>
                <h3><i class="bi bi-book-fill" aria-hidden="true"></i> Total Buku</h3>
                <p id="stat-total-buku"><?php echo $totalBuku; ?></p>
            </article>
            <article>
                <h3><i class="bi bi-people-fill" aria-hidden="true"></i> Total Anggota</h3>
                <p id="stat-total-anggota"><?php echo $totalAnggota; ?></p>
            </article>
            <article>
                <h3><i class="bi bi-journal-arrow-down" aria-hidden="true"></i> Sedang Dipinjam</h3>
                <p id="stat-sedang-dipinjam"><?php echo $sedangDipinjam; ?></p>
            </article>
        </section>

        <section data-role="petugas-only">
            <h2>Transaksi Terbaru</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transaksiTerbaru)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; color:#8a7a6d;">Belum ada transaksi.</td>
                        </tr>
                        <?php else: foreach ($transaksiTerbaru as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['nama_anggota']); ?></td>
                            <td><?php echo htmlspecialchars($t['judul_buku']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($t['tanggal_pinjam'])); ?></td>
                            <td>
                                <span class="badge-status badge-<?php echo $t['status'] === 'dipinjam' ? 'dipinjam' : 'dikembalikan'; ?>">
                                    <?php echo $t['status'] === 'dipinjam' ? 'Dipinjam' : 'Dikembalikan'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="index-section-link"><a href="transaksi/kembalikan.php">Lihat semua transaksi →</a></p>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.SIMPUS.requireLogin()) return;
            const namaEl = document.getElementById('namaPetugas');
            if (namaEl) namaEl.textContent = window.SIMPUS.getNama();
        });

        function toggleProfile(e) {
            e.preventDefault();
            document.getElementById("dropdownMenu").classList.toggle("show-dropdown");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn') && !event.target.closest('.profile-btn')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show-dropdown')) {
                        openDropdown.classList.remove('show-dropdown');
                    }
                }
            }
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str == null ? '' : str;
            return div.innerHTML;
        }

        // Auto refresh Ringkasan (petugas) + Buku Tersedia (semua orang) tiap 5 detik
        function muatUlangRingkasan() {
            fetch('api/ringkasan.php')
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    const b = document.getElementById('stat-total-buku');
                    const a = document.getElementById('stat-total-anggota');
                    const p = document.getElementById('stat-sedang-dipinjam');
                    if (b) b.textContent = data.total_buku;
                    if (a) a.textContent = data.total_anggota;
                    if (p) p.textContent = data.sedang_dipinjam;

                    const tabelBuku = document.getElementById('tabel-buku-tersedia');
                    if (tabelBuku) {
                        if (!data.buku_tersedia || data.buku_tersedia.length === 0) {
                            tabelBuku.innerHTML = '<tr><td colspan="3" style="text-align:center; color:#8a7a6d;">Belum ada buku tersedia saat ini.</td></tr>';
                        } else {
                            tabelBuku.innerHTML = data.buku_tersedia.map(function (buku) {
                                return '<tr>' +
                                    '<td>' + escapeHtml(buku.judul) + '</td>' +
                                    '<td>' + escapeHtml(buku.pengarang) + '</td>' +
                                    '<td>' + escapeHtml(buku.stok) + '</td>' +
                                '</tr>';
                            }).join('');
                        }
                    }
                })
                .catch(function () { /* diamkan kalau gagal */ });
        }

        if (document.getElementById('stat-total-buku') || document.getElementById('tabel-buku-tersedia')) {
            setInterval(muatUlangRingkasan, 5000);
        }
    </script>
</body>
</html>