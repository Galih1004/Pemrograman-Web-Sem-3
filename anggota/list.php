<?php
$page_title = "Daftar Anggota";
$extra_head = ['<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">'];
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

    <section>
      <h2>Daftar Anggota</h2>

      <?php if ($flash): ?>
      <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
      <?php endif; ?>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>No. Anggota</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>No. HP</th>
              <th>Tanggal Bergabung</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="daftar-anggota">
            <?php if (empty($daftarAnggota)): ?>
            <tr>
                <td colspan="6">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
            </tr>
            <?php else: ?>
                <?php foreach ($daftarAnggota as $anggota): ?>
                <tr>
                    <td><?php echo htmlspecialchars($anggota['no_anggota']); ?></td>
                    <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                    <td><?php echo htmlspecialchars($anggota['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($anggota['no_hp']); ?></td>
                    <td><?php echo htmlspecialchars($anggota['tanggal_bergabung']); ?></td>
                    <td>
                        <button type="button" class="btn-edit" data-id="<?php echo $anggota['id']; ?>">Edit</button>
                        <<button type="button" class="btn-delete" data-id="<?php echo $anggota['id']; ?>">Hapus</button>
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
      window.SIMPUS.requirePetugas();
    });
  </script>
<script> <!-- Fungsi Search-->
    document.getElementById('searchInput').addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const nama = row.children[1].textContent.toLowerCase();
            row.style.display = nama.includes(keyword) ? '' : 'none';
        });
    });
  </script>
<script> <!-- Fungsi Select Row-->
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        row.addEventListener('click', (e) => {
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'I') return;

            rows.forEach(r => r.classList.remove('selected'));
            row.classList.add('selected');
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
