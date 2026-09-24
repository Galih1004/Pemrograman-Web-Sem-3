<?php
$page_title = "Peminjaman Buku";
include __DIR__ . '/../includes/header.php';
?>

    <section>
      <h2>Form Peminjaman Buku</h2>
      <form id="formPinjam">
        <p>
          <label for="anggota">Anggota</label><br>
          <select id="anggota" name="anggota" required>
            <option value="">-- Pilih Anggota --</option>
            <option value="A001">A001 - Tom Holland</option>
            <option value="A002">A002 - Jean Grey</option>
            <option value="A003">A003 - Peter Parker</option>
            <option value="A004">A004 - Wanda Maximoff</option>
          </select>
        </p>
        <p>
          <label for="buku">Buku</label><br>
          <select id="buku" name="buku" required>
            <option value="">-- Pilih Buku (stok &gt; 0) --</option>
            <option value="Laskar Pelangi">Laskar Pelangi (4 tersedia)</option>
            <option value="Bumi Manusia">Bumi Manusia (2 tersedia)</option>
            <option value="Filosofi Teras">Filosofi Teras (5 tersedia)</option>
            <option value="Ronggeng Dukuh Paruk">Ronggeng Dukuh Paruk (1 tersedia)</option>
            <option value="Sang Pemimpi">Sang Pemimpi (3 tersedia)</option>
            <option value="Cantik Itu Luka">Cantik Itu Luka (2 tersedia)</option>
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

      // Isi tanggal pinjam otomatis dengan hari ini
      const tgl = document.getElementById('tanggal_pinjam');
      tgl.value = new Date().toISOString().slice(0, 10);
    });

    document.getElementById('formPinjam').addEventListener('submit', function (e) {
      e.preventDefault();
      const anggota = document.getElementById('anggota');
      const buku = document.getElementById('buku');

      if (!anggota.value || !buku.value) {
        alert('Anggota dan buku wajib dipilih.');
        return;
      }

      alert('Peminjaman "' + buku.options[buku.selectedIndex].text + '" oleh ' +
            anggota.options[anggota.selectedIndex].text + ' berhasil disimpan.\n' +
            '(Stok buku otomatis berkurang 1)');
      window.location.href = '../index.php';
    });

    /* SCRIPT TAMBAHAN UNTUK DROPDOWN PROFIL */
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
