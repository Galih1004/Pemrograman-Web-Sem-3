<?php
$page_title = "Daftar Buku";
$extra_head = ['<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">'];
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

    <section>
      <h2>Daftar Buku</h2>
      <p data-role="tamu-only" style="margin-bottom:1rem; color:#8a7a6d;">Katalog buku perpustakaan. Untuk meminjam, silakan hubungi petugas.</p>

      <?php if ($flash): ?>
      <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
      <?php endif; ?>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Judul</th>
              <th>Pengarang</th>
              <th>Tahun</th>
              <th>Stok</th>
              <th class="aksi-petugas">Aksi</th>
            </tr>
          </thead>
          <tbody id="daftar-buku">
            <?php if (empty($daftarBuku)): ?>
            <tr>
                <td colspan="5">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
            </tr>
            <?php else: ?>
                <?php foreach ($daftarBuku as $buku): ?>
                <tr>
                    <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                    <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                    <td><?php echo htmlspecialchars($buku['tahun']); ?></td>
                    <td><?php echo htmlspecialchars($buku['stok']); ?></td>
                    <td class="aksi-petugas">
                        <button type="button" class="btn-edit" data-id="<?php echo $buku['id']; ?>">Edit</button>
                        <button type="button" class="btn-delete" data-id="<?php echo $buku['id']; ?>">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      window.SIMPUS.requireLogin();
    });
  </script>
<script> <!-- Fungsi Search-->
    document.getElementById('searchInput').addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const judul = row.children[0].textContent.toLowerCase();
            row.style.display = judul.includes(keyword) ? '' : 'none';
        });
    });
  </script>
<script> <!-- Fungsi Select Row-->
    document.querySelectorAll('table tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'I') return;

            document.querySelectorAll('table tbody tr').forEach(r => r.classList.remove('selected'));
            this.classList.add('selected');
        });
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