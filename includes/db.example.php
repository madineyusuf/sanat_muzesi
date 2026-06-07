<?php
// Bu dosyayı kopyalayıp "db.php" olarak kaydedin
// ve kendi bilgilerinizi girin.

$host = 'localhost';
$db   = 'VERITABANI_ADI';
$user = 'KULLANICI_ADI';
$pass = 'SIFRE';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
