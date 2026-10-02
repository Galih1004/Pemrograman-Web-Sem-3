<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host     = getenv('PGHOST') ?: 'localhost';
$port     = getenv('PGPORT') ?: '5432';
$dbname   = getenv('PGDATABASE') ?: 'railway';
$user     = getenv('PGUSER') ?: 'postgres';
$password = getenv('PGPASSWORD') ?: '';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO_ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>