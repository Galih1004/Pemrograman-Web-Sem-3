<?php
$page_title = "Pengembalian Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Ambil semua peminjaman yang statusnya masih "dipinjam" (belum dikembalikan)
$transaksiAktif = $pdo->query(
    "SELECT p.id, a.nama AS nama_anggota, b.judul AS judul_buku, p.tanggal_pinjam
     FROM peminjaman p
     JOIN anggota a ON a.id = p.anggota_id
     JOIN buku b ON b.id = p.buku_id
     WHERE p.status = 'dipinjam'
     ORDER BY p.tanggal_pinjam ASC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

    <section>
      <h2>Pengembalian Buku</h2>

      <?php if ($flash): ?>
      <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
      <?php endif; ?>

      <p style="margin-bottom:0.75rem; color:#57443a; font-weight:600;">Cari transaksi aktif:</p>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Anggota</th>
              <th>Buku</th>
              <th>Tgl Pinjam</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tabelPengembalian">
            <?php if (empty($transaksiAktif)): ?>
            <tr>
                <td colspan="4" style="text-align:center; color:#8a7a6d;">Tidak ada peminjaman yang sedang aktif.</td>
            </tr>
            <?php else: foreach ($transaksiAktif as $t): ?>
            <tr>
              <td><?php echo htmlspecialchars($t['nama_anggota']); ?></td>
              <td><?php echo htmlspecialchars($t['judul_buku']); ?></td>
              <td><?php echo date('d/m/Y', strtotime($t['tanggal_pinjam'])); ?></td>
              <td>
                <button type="button" class="btn-kembalikan" data-id="<?php echo $t['id']; ?>">Kembalikan</button>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      window.SIMPUS.requirePetugas();
    });

    // Pencarian transaksi aktif (nama anggota / judul buku)
    document.getElementById('searchInput').addEventListener('input', function () {
      const keyword = this.value.toLowerCase();
      document.querySelectorAll('#tabelPengembalian tr').forEach(function (row) {
        const teks = row.textContent.toLowerCase();
        row.style.display = teks.includes(keyword) ? '' : 'none';
      });
    });

    // Tandai "Dikembalikan" — kirim ke server, bukan cuma ubah tampilan
    document.getElementById('tabelPengembalian').addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-kembalikan');
      if (!btn) return;

      const row = btn.closest('tr');
      const buku = row.children[1].textContent;
      const id = btn.dataset.id;
      const yakin = confirm('Tandai buku "' + buku + '" sebagai dikembalikan?');
      if (!yakin || !id) return;

      window.location.href = 'proses_kembalikan.php?id=' + encodeURIComponent(id);
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
  </script>
</body>
</html>