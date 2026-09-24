<?php
$page_title = "Tambah Buku";
$extra_scripts = ['assets/js/app.js', 'assets/js/buku.js'];
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

    <section>
      <h2>Tambah Buku Baru</h2>

      <?php if ($flash): ?>
      <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
      <?php endif; ?>

      <form id="form-tambah" method="post" action="proses_tambah.php">
        <p>
          <label for="judul">Judul</label><br>
          <input type="text" id="judul" name="judul" required>
        </p>
        <p>
          <label for="pengarang">Pengarang</label><br>
          <input type="text" id="pengarang" name="pengarang" required>
        </p>
        <p>
          <label for="tahun">Tahun Terbit</label><br>
          <input type="number" id="tahun" name="tahun" min="1900" max="2026" required>
        </p>
        <p>
          <label for="isbn">ISBN</label><br>
          <input type="text" id="isbn" name="isbn">
        </p>
        <p>
          <label for="stok">Stok</label><br>
          <input type="number" id="stok" name="stok" min="0" required>
        </p>
        <p>
          <label for="kategori">Kategori</label><br>
          <select id="kategori" name="kategori">
            <option value="fiksi">Fiksi</option>
            <option value="non-fiksi">Non-Fiksi</option>
            <option value="referensi">Referensi</option>
          </select>
        </p>
        <p>
          <button type="submit">Simpan</button>
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
