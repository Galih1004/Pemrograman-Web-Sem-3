<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';
$kategori = trim($_POST['kategori'] ?? '');

$errors = [];
if ($judul === '') $errors[] = "Judul wajib diisi.";
if ($pengarang === '') $errors[] = "Pengarang wajib diisi.";
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) $errors[] = "Tahun harus di antara 1900-2026.";
if (!is_numeric($stok) || $stok < 0) $errors[] = "Stok tidak boleh negatif.";

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php' . ($id ? '?id=' . urlencode($id) : ''));
    exit;
}

try {
    if ($id) {
        // Edit buku
        $stmt = $pdo->prepare(
            "UPDATE buku SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
                    isbn = :isbn, stok = :stok, kategori = :kategori
             WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id, 'judul' => $judul, 'pengarang' => $pengarang,
            'tahun' => (int) $tahun, 'isbn' => $isbn, 'stok' => (int) $stok, 'kategori' => $kategori,
        ]);
        $pesan = 'Buku berhasil diperbarui.';
    } else {
        // Tambah baru
        $stmt = $pdo->prepare(
            "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
             VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
        );
        $stmt->execute([
            'judul' => $judul, 'pengarang' => $pengarang, 'tahun' => (int) $tahun,
            'isbn' => $isbn, 'stok' => (int) $stok, 'kategori' => $kategori,
        ]);
        $pesan = 'Buku berhasil ditambahkan.';
    }
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menyimpan data buku.'];
    header('Location: tambah.php' . ($id ? '?id=' . urlencode($id) : ''));
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => $pesan];
header('Location: list.php');
exit;