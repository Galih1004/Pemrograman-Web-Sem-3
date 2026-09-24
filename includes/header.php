<?php
session_start();
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$__projectRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__projectRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1)
?>;
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digipus<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
    <?php if (!empty($extra_head)): foreach ($extra_head as $tag): ?>
    <?php echo $tag; ?>
    <?php endforeach; endif; ?>
</head>
<body>
    <header>
        <h1>📚 Digipus</h1>
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <label for="nav-toggle" class="nav-toggle-label">&#9776;</label>
        <form class="search-form" onsubmit="return false;">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari...">
        </form>
        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
                <li><a href="<?php echo $base; ?>buku/list.php">Daftar Buku</a></li>
                <li data-role="petugas-only"><a href="<?php echo $base; ?>buku/tambah.php">Tambah Buku</a></li>
                <li data-role="petugas-only"><a href="<?php echo $base; ?>anggota/list.php">Daftar Anggota</a></li>
                <li data-role="petugas-only"><a href="<?php echo $base; ?>anggota/tambah.php">Tambah Anggota</a></li>
                <li data-role="petugas-only"><a href="<?php echo $base; ?>transaksi/pinjam.php">Peminjaman</a></li>
                <li data-role="petugas-only"><a href="<?php echo $base; ?>transaksi/kembalikan.php">Pengembalian</a></li>

                <li class="profile-dropdown">
                    <a href="#" class="profile-btn" onclick="toggleProfile(event)">
                        <span class="nav-user-badge"></span> ▼
                    </a>
                    <div id="dropdownMenu" class="profile-dropdown-content">
                        <a href="#" onclick="alert('Fitur Profile sedang dalam pengembangan.'); return false;">👤 Lihat Profile</a>
                        <a href="<?php echo $base; ?>akun/login.php" class="logout" style="color: red !important;">🚪 Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
