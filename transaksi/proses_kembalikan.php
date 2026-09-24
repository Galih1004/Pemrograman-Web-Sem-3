<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Transaksi tidak valid.'];
    header('Location: kembalikan.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // ambil transaksi peminjaman
    $stmt = $pdo->prepare("SELECT buku_id FROM peminjaman WHERE id = :id AND status = 'dipinjam' FOR UPDATE");
    $stmt->execute(['id' => $id]);
    $transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaksi) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Transaksi tidak ditemukan atau sudah dikembalikan.'];
        header('Location: kembalikan.php');
        exit;
    }

    // update status transaksi
    $pdo->prepare(
        "UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURRENT_DATE WHERE id = :id"
    )->execute(['id' => $id]);

    // stok otomatis bertambah
    $pdo->prepare(
        "UPDATE buku SET stok = stok + 1 WHERE id = :buku_id"
    )->execute(['buku_id' => $transaksi['buku_id']]);

    $pdo->commit();
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditandai sebagai dikembalikan.'];
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal memproses pengembalian.'];
}

header('Location: kembalikan.php');
exit;