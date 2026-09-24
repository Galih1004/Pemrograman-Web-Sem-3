<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') $errors[] = "Nama wajib diisi.";
if ($noAnggota === '') $errors[] = "No. Anggota wajib diisi.";

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php' . ($id ? '?id=' . urlencode($id) : ''));
    exit;
}

try {
    if ($id) {
        // MODE EDIT
        $stmt = $pdo->prepare(
            "UPDATE anggota SET nama = :nama, no_anggota = :no_anggota, alamat = :alamat, no_hp = :no_hp
             WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id, 'nama' => $nama, 'no_anggota' => $noAnggota,
            'alamat' => $alamat, 'no_hp' => $noHp,
        ]);
        $pesan = 'Anggota berhasil diperbarui.';
    } else {
        // MODE TAMBAH
        $stmt = $pdo->prepare(
            "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
             VALUES (:nama, :no_anggota, :alamat, :no_hp)"
        );
        $stmt->execute([
            'nama' => $nama, 'no_anggota' => $noAnggota,
            'alamat' => $alamat, 'no_hp' => $noHp,
        ]);
        $pesan = 'Anggota berhasil ditambahkan.';
    }
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menyimpan: No. Anggota mungkin sudah terdaftar.'];
    header('Location: tambah.php' . ($id ? '?id=' . urlencode($id) : ''));
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => $pesan];
header('Location: list.php');
exit;