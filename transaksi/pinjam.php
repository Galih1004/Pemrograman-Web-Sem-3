<?php
$page_title = "Peminjaman Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = $pdo->query("SELECT id, no_anggota, nama FROM anggota ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
$daftarBuku = $pdo->query("SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul")->fetchAll(PDO::FETCH_ASSOC);
?>

    <section>
      <h2>Form Peminjaman Buku</h2>

      <?php if ($flash): ?>
      <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
      <?php endif; ?>

      <form method="post" action="proses_pinjam.php">
        <p>
          <label for="anggota">Anggota</label><br>
          <select id="anggota" name="anggota_id" required>
            <option value="">-- Pilih Anggota --</option>
            <?php foreach ($daftarAnggota as $a): ?>
            <option value="<?php echo $a['id']; ?>">
                <?php echo htmlspecialchars($a['no_anggota'] . ' - ' . $a['nama']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </p>
        <p>
          <label for="buku">Buku</label><br>
          <select id="buku" name="buku_id" required>
            <option value="">-- Pilih Buku (stok &gt; 0) --</option>
            <?php foreach ($daftarBuku as $b): ?>
            <option value="<?php echo $b['id']; ?>">
                <?php echo htmlspecialchars($b['judul'] . ' (' . $b['stok'] . ' tersedia)'); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </p>
        <p>
          <label for="tanggal_pinjam">Tanggal Pinjam</label><br>
          <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required>
          <small style="color:#8a7a6d;">(Otomatis: hari ini)</small>
        </p>
        <p>
          <button type="submit">Simpan Peminjaman</button>
        </p>
      </form>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      window.SIMPUS.requirePetugas();
      const tgl = document.getElementById('tanggal_pinjam');
      tgl.value = new Date().toISOString().slice(0, 10);
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