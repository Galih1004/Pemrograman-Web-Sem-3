<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$anggotaId = $_POST['anggota_id'] ?? '';
$bukuId = $_POST['buku_id'] ?? '';
$tanggalPinjam = $_POST['tanggal_pinjam'] ?? '';

if ($anggotaId === '' || $bukuId === '' || $tanggalPinjam === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota, buku, dan tanggal pinjam wajib diisi.'];
    header('Location: pinjam.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // cek stok buku dengan FOR UPDATE untuk menghindari race condition
    $cek = $pdo->prepare("SELECT stok FROM buku WHERE id = :id FOR UPDATE");
    $cek->execute(['id' => $bukuId]);
    $buku = $cek->fetch(PDO::FETCH_ASSOC);

    if (!$buku || $buku['stok'] < 1) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Stok buku sudah habis.'];
        header('Location: pinjam.php');
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO peminjaman (anggota_id, buku_id, tanggal_pinjam, status)
         VALUES (:anggota_id, :buku_id, :tanggal_pinjam, 'dipinjam')"
    );
    $stmt->execute([
        'anggota_id' => $anggotaId,
        'buku_id' => $bukuId,
        'tanggal_pinjam' => $tanggalPinjam,
    ]);

    $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :id")
        ->execute(['id' => $bukuId]);

    $pdo->commit();
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Peminjaman berhasil disimpan.'];
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menyimpan peminjaman.'];
}

header('Location: pinjam.php');
exit;