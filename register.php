<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullanici_adi = trim($_POST['kullanici_adi'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $sifre         = $_POST['sifre'] ?? '';

    if (!$kullanici_adi || !$email || !$sifre) {
        $error = 'Tüm alanları doldurun.';
    } elseif (strlen($sifre) < 6) {
        $error = 'Şifre en az 6 karakter olmalı.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM kullanicilar WHERE kullanici_adi = ? OR email = ?");
        $stmt->execute([$kullanici_adi, $email]);
        if ($stmt->fetch()) {
            $error = 'Bu kullanıcı adı veya e-posta zaten kullanımda.';
        } else {
            $hash = password_hash($sifre, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO kullanicilar (kullanici_adi, email, sifre_hash) VALUES (?, ?, ?)");
            $stmt->execute([$kullanici_adi, $email, $hash]);
            $success = 'Kayıt başarılı!';
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="container mt-5" style="max-width: 450px">
    <h2 class="mb-4">Kayıt Ol</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= $success ?> <a href="login.php">Giriş yapın</a>.
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kullanıcı Adı</label>
            <input type="text" name="kullanici_adi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">E-posta</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Şifre</label>
            <input type="password" name="sifre" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
    </form>

    <p class="mt-3 text-center">Hesabın var mı? <a href="login.php">Giriş yap</a></p>
</div>

<?php require_once 'includes/footer.php'; ?>
