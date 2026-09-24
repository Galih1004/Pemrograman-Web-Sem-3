<?php
$page_title = "Tambah Buku";
$extra_scripts = ['assets/js/app.js'];
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$buku = null;
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $buku = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$buku) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Buku tidak ditemukan.'];
        header('Location: list.php');
        exit;
    }
}
?>

    <section>
      <h2><?php echo $buku ? 'Edit Buku' : 'Tambah Buku Baru'; ?></h2>

      <?php if ($flash): ?>
      <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
      <?php endif; ?>

      <form id="form-tambah" method="post" action="proses_tambah.php">
        <?php if ($buku): ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($buku['id']); ?>">
        <?php endif; ?>
        <p>
          <label for="judul">Judul</label><br>
          <input type="text" id="judul" name="judul" required
                 value="<?php echo htmlspecialchars($buku['judul'] ?? ''); ?>">
        </p>
        <p>
          <label for="pengarang">Pengarang</label><br>
          <input type="text" id="pengarang" name="pengarang" required
                 value="<?php echo htmlspecialchars($buku['pengarang'] ?? ''); ?>">
        </p>
        <p>
          <label for="tahun">Tahun Terbit</label><br>
          <input type="number" id="tahun" name="tahun" min="1900" max="2026" required
                 value="<?php echo htmlspecialchars($buku['tahun'] ?? ''); ?>">
        </p>
        <p>
          <label for="isbn">ISBN</label><br>
          <input type="text" id="isbn" name="isbn"
                 value="<?php echo htmlspecialchars($buku['isbn'] ?? ''); ?>">
        </p>
        <p>
          <label for="stok">Stok</label><br>
          <input type="number" id="stok" name="stok" min="0" required
                 value="<?php echo htmlspecialchars($buku['stok'] ?? ''); ?>">
        </p>
        <p>
          <label for="kategori">Kategori</label><br>
          <select id="kategori" name="kategori">
            <?php $kat = $buku['kategori'] ?? 'fiksi'; ?>
            <option value="fiksi" <?php echo $kat === 'fiksi' ? 'selected' : ''; ?>>Fiksi</option>
            <option value="non-fiksi" <?php echo $kat === 'non-fiksi' ? 'selected' : ''; ?>>Non-Fiksi</option>
            <option value="referensi" <?php echo $kat === 'referensi' ? 'selected' : ''; ?>>Referensi</option>
          </select>
        </p>
        <p>
          <button type="submit"><?php echo $buku ? 'Simpan Perubahan' : 'Simpan'; ?></button>
        </p>
      </form>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      window.SIMPUS.requirePetugas();
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