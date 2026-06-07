<?php
$host = 'localhost';
$db   = 'dbstorage24360859922';
$user = 'dbusr24360859922';
$pass = 'SxH5hSixGy8h';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
