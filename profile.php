<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$kullanici_id = $_SESSION['kullanici_id'];
$success = '';
$error   = '';

// Profil güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yeni_ad    = trim($_POST['kullanici_adi'] ?? '');
    $yeni_sifre = $_POST['sifre'] ?? '';

    if ($yeni_ad && $yeni_ad !== $_SESSION['kullanici_adi']) {
        // Kullanıcı adı başkası tarafından kullanılıyor mu?
        $kontrol = $pdo->prepare("SELECT id FROM kullanicilar WHERE kullanici_adi = ? AND id != ?");
        $kontrol->execute([$yeni_ad, $kullanici_id]);
        if ($kontrol->fetch()) {
            $error = 'Bu kullanıcı adı zaten kullanımda.';
        } else {
            $pdo->prepare("UPDATE kullanicilar SET kullanici_adi = ? WHERE id = ?")
                ->execute([$yeni_ad, $kullanici_id]);
            $_SESSION['kullanici_adi'] = $yeni_ad;
            $success = 'Kullanıcı adı güncellendi.';
        }
    }

    if (!$error && $yeni_sifre) {
        if (strlen($yeni_sifre) < 6) {
            $error = 'Şifre en az 6 karakter olmalı.';
        } else {
            $hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE kullanicilar SET sifre_hash = ? WHERE id = ?")
                ->execute([$hash, $kullanici_id]);
            $success = 'Şifre güncellendi.';
        }
    }
}

// Favori eserler
$stmt = $pdo->prepare("
    SELECT e.* FROM eserler e
    JOIN favoriler f ON f.eser_id = e.id
    WHERE f.kullanici_id = ?
    ORDER BY f.eklenme_tarihi DESC
");
$stmt->execute([$kullanici_id]);
$favoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <div class="row g-4">

        <!-- Profil Düzenleme -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Profil Bilgileri</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Kullanıcı Adı</label>
                            <input type="text" name="kullanici_adi" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['kullanici_adi']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Yeni Şifre
                                <small class="text-muted">(boş bırakırsan değişmez)</small>
                            </label>
                            <input type="password" name="sifre" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Güncelle
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Favori Eserler -->
        <div class="col-md-8">
            <h4 class="mb-3">
                <i class="bi bi-heart-fill text-danger"></i> Favori Eserlerim
                <span class="badge bg-danger"><?= count($favoriler) ?></span>
            </h4>

            <?php if (empty($favoriler)): ?>
                <div class="text-center text-muted mt-5">
                    <i class="bi bi-heart fs-1"></i>
                    <p class="mt-2">Henüz favori eser eklemediniz.</p>
                    <a href="index.php" class="btn btn-outline-primary">Eserleri Keşfet</a>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php foreach ($favoriler as $eser): ?>
                        <div class="col" id="kart-<?= $eser['id'] ?>">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= htmlspecialchars($eser['gorsel_yolu'] ?? 'assets/images/placeholder.jpg') ?>"
                                     class="card-img-top"
                                     style="height: 160px; object-fit: cover;"
                                     alt="<?= htmlspecialchars($eser['eser_adi']) ?>">
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($eser['eser_adi']) ?></h6>
                                    <p class="card-text text-muted small">
                                        <?= htmlspecialchars($eser['sanatci']) ?>
                                    </p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="artwork.php?id=<?= $eser['id'] ?>"
                                       class="btn btn-sm btn-outline-primary">İncele</a>
                                    <button class="btn btn-sm btn-danger fav-btn"
                                            data-id="<?= $eser['id'] ?>">
                                        <i class="bi bi-heart-fill"></i> Çıkar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.querySelectorAll('.fav-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const res = await fetch('api/toggle_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `eser_id=${id}`
        });
        const data = await res.json();
        if (data.status === 'removed') {
            document.getElementById(`kart-${id}`)?.remove();
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
