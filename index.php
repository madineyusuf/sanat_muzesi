<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$tur   = $_GET['tur'] ?? '';
$arama = $_GET['arama'] ?? '';

$sql    = "SELECT * FROM eserler WHERE 1=1";
$params = [];

if ($tur) {
    $sql .= " AND tur = ?";
    $params[] = $tur;
}
if ($arama) {
    $sql .= " AND (eser_adi LIKE ? OR sanatci LIKE ?)";
    $params[] = "%$arama%";
    $params[] = "%$arama%";
}
$sql .= " ORDER BY eklenme_tarihi DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$eserler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$favori_ids = [];
if (isLoggedIn()) {
    $fav = $pdo->prepare("SELECT eser_id FROM favoriler WHERE kullanici_id = ?");
    $fav->execute([$_SESSION['kullanici_id']]);
    $favori_ids = $fav->fetchAll(PDO::FETCH_COLUMN);
}

$turler = $pdo->query("SELECT DISTINCT tur FROM eserler ORDER BY tur")->fetchAll(PDO::FETCH_COLUMN);
?>
<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">

    <div class="text-center mb-5">
        <h1 class="display-5">Sanat Müzesi</h1>
        <div class="green-divider"></div>
        <p class="text-muted">Dünyanın en ünlü eserlerini keşfedin</p>
    </div>

    <form class="row g-2 mb-4" method="GET">
        <div class="col-md-6">
            <input type="text" name="arama" class="form-control"
                   placeholder="Eser adı veya sanatçı..."
                   value="<?= htmlspecialchars($arama) ?>">
        </div>
        <div class="col-md-4">
            <select name="tur" class="form-select">
                <option value="">Tüm Kategoriler</option>
                <?php foreach ($turler as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>" <?= $tur === $t ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Ara</button>
        </div>
    </form>

    <?php if (empty($eserler)): ?>
        <div class="text-center text-muted mt-5">
            <i class="bi bi-search fs-1"></i>
            <p class="mt-2">Eser bulunamadı.</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($eserler as $eser): ?>
            <div class="col">
                <div class="art-card">
                    <div class="img-wrapper">
                        <img src="<?= htmlspecialchars($eser['gorsel_yolu'] ?? '/~st24360859922/assets/images/placeholder.jpg') ?>"
                             alt="<?= htmlspecialchars($eser['eser_adi']) ?>">

                        <?php if (isLoggedIn()): ?>
                            <button class="fav-btn <?= in_array($eser['id'], $favori_ids) ? 'active' : '' ?>"
                                    data-id="<?= $eser['id'] ?>">
                                <i class="bi bi-heart<?= in_array($eser['id'], $favori_ids) ? '-fill' : '' ?>"></i>
                            </button>
                        <?php endif; ?>

                        <div class="card-info">
                            <div>
                                <h3><?= htmlspecialchars($eser['eser_adi']) ?></h3>
                                <p><?= htmlspecialchars($eser['sanatci']) ?> · <?= htmlspecialchars($eser['yil']) ?></p>
                            </div>
                            <a href="/~st24360859922/artwork.php?id=<?= $eser['id'] ?>" class="incele-btn">İncele</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
document.querySelectorAll('.fav-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const res = await fetch('/~st24360859922/api/toggle_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `eser_id=${id}`
        });
        const data = await res.json();
        const icon = btn.querySelector('i');
        if (data.status === 'added') {
            btn.classList.add('active');
            icon.classList.replace('bi-heart', 'bi-heart-fill');
        } else {
            btn.classList.remove('active');
            icon.classList.replace('bi-heart-fill', 'bi-heart');
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
