<?php
$page_title = "Tambah Anggota";
$extra_scripts = ['assets/js/app.js'];
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$anggota = null;
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $anggota = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$anggota) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota tidak ditemukan.'];
        header('Location: list.php');
        exit;
    }
}
?>

<section>
    <h2><?php echo $anggota ? 'Edit Anggota' : 'Tambah Anggota'; ?></h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php">
        <?php if ($anggota): ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($anggota['id']); ?>">
        <?php endif; ?>
        <p>
            <label for="nama">Nama</label><br>
            <input type="text" id="nama" name="nama" required
                   value="<?php echo htmlspecialchars($anggota['nama'] ?? ''); ?>">
        </p>
        <p>
            <label for="no_anggota">No. Anggota</label><br>
            <input type="text" id="no_anggota" name="no_anggota" required
                   value="<?php echo htmlspecialchars($anggota['no_anggota'] ?? ''); ?>">
        </p>
        <p>
            <label for="alamat">Alamat</label><br>
            <input type="text" id="alamat" name="alamat"
                   value="<?php echo htmlspecialchars($anggota['alamat'] ?? ''); ?>">
        </p>
        <p>
            <label for="no_hp">No. HP</label><br>
            <input type="text" id="no_hp" name="no_hp"
                   value="<?php echo htmlspecialchars($anggota['no_hp'] ?? ''); ?>">
        </p>
        <p>
            <button type="submit"><?php echo $anggota ? 'Simpan Perubahan' : 'Simpan'; ?></button>
        </p>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>