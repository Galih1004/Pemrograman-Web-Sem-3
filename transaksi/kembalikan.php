<?php
$page_title = "Pengembalian Buku";
include __DIR__ . '/../includes/header.php';
?>

    <section>
      <h2>Pengembalian Buku</h2>
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
            <tr>
              <td>Wanda Maximoff</td>
              <td>Bumi Manusia</td>
              <td>15/05/2024</td>
              <td><button type="button" class="btn-kembalikan">Kembalikan</button></td>
            </tr>
            <tr>
              <td>Peter Parker</td>
              <td>Laskar Pelangi</td>
              <td>18/05/2024</td>
              <td><button type="button" class="btn-kembalikan">Kembalikan</button></td>
            </tr>
            <tr>
              <td>Jean Grey</td>
              <td>Negeri 5 Menara</td>
              <td>19/05/2024</td>
              <td><button type="button" class="btn-kembalikan">Kembalikan</button></td>
            </tr>
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

    // Tandai "Dikembalikan" (stok buku otomatis bertambah 1)
    document.getElementById('tabelPengembalian').addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-kembalikan');
      if (!btn) return;

      const row = btn.closest('tr');
      const buku = row.children[1].textContent;
      const yakin = confirm('Tandai buku "' + buku + '" sebagai dikembalikan?');
      if (!yakin) return;

      btn.textContent = 'Dikembalikan';
      btn.disabled = true;
      btn.style.opacity = '0.6';
      btn.style.cursor = 'default';
      alert('Pengembalian dicatat. Stok buku "' + buku + '" bertambah 1.');
    });

    function toggleProfile(e) {
        e.preventDefault();
        document.getElementById("dropdownMenu").classList.toggle("show-dropdown");
    }

    // Menutup dropdown jika user mengklik area lain di luar tombol
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
