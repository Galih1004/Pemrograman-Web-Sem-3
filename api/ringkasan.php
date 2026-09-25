<?php
header('Content-Type: application/json');
require __DIR__ . '/../includes/koneksi.php';

$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$sedangDipinjam = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();

// Buku yang stoknya masih tersedia (untuk ditampilkan ke Tamu & Petugas)
$bukuTersedia = $pdo->query(
    "SELECT judul, pengarang, stok FROM buku WHERE stok > 0 ORDER BY judul ASC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'total_buku' => (int) $totalBuku,
    'total_anggota' => (int) $totalAnggota,
    'sedang_dipinjam' => (int) $sedangDipinjam,
    'buku_tersedia' => $bukuTersedia,
]);