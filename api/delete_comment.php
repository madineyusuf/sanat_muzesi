<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$yorum_id = (int)($_GET['id'] ?? 0);
$eser_id  = (int)($_GET['eser_id'] ?? 0);

if (!$yorum_id || !$eser_id) {
    header('Location: ../index.php');
    exit;
}

// Sadece kendi yorumunu silebilir
$stmt = $pdo->prepare("DELETE FROM yorumlar WHERE id = ? AND kullanici_id = ?");
$stmt->execute([$yorum_id, $_SESSION['kullanici_id']]);

header("Location: ../artwork.php?id=$eser_id");
exit;
