<?php
// Sesuaikan $user/$pass dengan environment lokal kamu
// (default PostgreSQL: user "postgres", password yang kamu isi saat instalasi/Laragon Quick Add).
$host = "localhost";
$port = "5432";
$db   = "digipus";
$user = "postgres";
$pass = "postgres";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
