<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM eserler WHERE id = ?");
$stmt->execute([$id]);
$eser = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$eser) {
    header('Location: index.php');
    exit;
}

// Yorum gönderme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $icerik = trim($_POST['icerik'] ?? '');
    if ($icerik) {
        $ins = $pdo->prepare("INSERT INTO yorumlar (kullanici_id, eser_id, icerik) VALUES (?, ?, ?)");
        $ins->execute([$_SESSION['kullanici_id'], $id, $icerik]);
        header("Location: artwork.php?id=$id");
        exit;
    }
}

// Yorumları çek
$stmt2 = $pdo->prepare("
    SELECT y.*, k.kullanici_adi
    FROM yorumlar y
    JOIN kullanicilar k ON k.id = y.kullanici_id
    WHERE y.eser_id = ?
    ORDER BY y.olusturma_tarihi DESC
");
$stmt2->execute([$id]);
$yorumlar = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Favori durumu
$favori = false;
if (isLoggedIn()) {
    $f = $pdo->prepare("SELECT id FROM favoriler WHERE kullanici_id = ? AND eser_id = ?");
    $f->execute([$_SESSION['kullanici_id'], $id]);
    $favori = (bool)$f->fetch();
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">

    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">
        <i class="bi bi-arrow-left"></i> Geri Dön
    </a>

    <!-- Обернули всю карточку экспоната в один красивый полупрозрачный контейнер -->
    <div class="card border-0 shadow-sm mb-5" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(10px); border-radius: 15px;">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4 align-items-center"> <!-- g-4 добавит аккуратные зазоры между колонками -->
                
                <!-- Левая колонка: Ограничиваем высоту контейнера для картинок, чтобы они не ломали верстку -->
                <div class="col-md-6">
                    <div style="height: 450px; width: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 10px;">
                        <img src="<?= htmlspecialchars($eser['gorsel_yolu'] ?? 'assets/images/placeholder.jpg') ?>"
                             style="max-height: 100%; max-width: 100%; object-fit: contain;" 
                             class="shadow-sm" 
                             alt="<?= htmlspecialchars($eser['eser_adi']) ?>">
                    </div>
                </div>

                <!-- Правая колонка: Текстовая информация -->
                <div class="col-md-6">
                    <h1 class="fw-bold text-dark mb-1"><?= htmlspecialchars($eser['eser_adi']) ?></h1>
                    <p class="text-muted fs-5 mb-2">
                        <?= htmlspecialchars($eser['sanatci']) ?> · <?= htmlspecialchars($eser['yil']) ?>
                    </p>
                    <span class="badge bg-secondary px-3 py-2 mb-4"><?= htmlspecialchars($eser['tur']) ?></span>
                    
                    <!-- Текст описания -->
                    <div class="text-secondary lh-lg mb-4" style="font-size: 1.05rem; text-align: justify;">
                        <?= nl2br(htmlspecialchars($eser['aciklama'])) ?>
                    </div>

                    <?php if (isLoggedIn()): ?>
                        <button class="btn fav-btn <?= $favori ? 'btn-danger' : 'btn-outline-danger' ?> px-4 py-2"
                                data-id="<?= $id ?>">
                            <i class="bi bi-heart<?= $favori ? '-fill' : '' ?>"></i>
                            <span><?= $favori ? 'Favorilerimde' : 'Favorilere Ekle' ?></span>
                        </button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-danger px-4 py-2">
                            <i class="bi bi-heart"></i> Favorilere eklemek için giriş yapın
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div> <!-- Конец карточки экспоната -->

    <!-- Разделитель: Гарантирует, что комментарии начнутся строго СНИЗУ и не наползут на блоки выше -->
    <div class="clearfix"></div>

    <!-- Раздел комментариев (остается на всю ширину контейнера внизу) -->
    <div class="mt-5 pt-3 border-top">
        <button class="btn btn-outline-secondary mb-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#yorumlar">
            <i class="bi bi-chat-dots"></i> Yorumlar (<?= count($yorumlar) ?>)
        </button>

        <div class="collapse" id="yorumlar">
            <?php if (isLoggedIn()): ?>
                <form method="POST" class="mb-4" style="max-width: 600px;">
                    <div class="mb-2">
                        <textarea name="icerik" class="form-control" rows="3"
                                  placeholder="Yorumunuzu yazın..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Gönder</button>
                </form>
            <?php else: ?>
                <p class="text-muted">
                    <a href="login.php">Giriş yapın</a> ve yorum bırakın.
                </p>
            <?php endif; ?>

            <?php if (empty($yorumlar)): ?>
                <p class="text-muted">Henüz yorum yok. İlk yorumu siz yapın!</p>
            <?php endif; ?>

            <div style="max-width: 800px;">
                <?php foreach ($yorumlar as $yorum): ?>
                    <div class="card mb-2 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8);">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark"><?= htmlspecialchars($yorum['kullanici_adi']) ?></strong>
                                <small class="text-muted" style="font-size: 0.8rem;"><?= $yorum['olusturma_tarihi'] ?></small>
                            </div>
                            <p class="mb-2 mt-1 text-secondary"><?= nl2br(htmlspecialchars($yorum['icerik'])) ?></p>
                            <?php if (isLoggedIn() && $_SESSION['kullanici_id'] == $yorum['kullanici_id']): ?>
                                <a href="api/delete_comment.php?id=<?= $yorum['id'] ?>&eser_id=<?= $id ?>"
                                   class="btn btn-sm btn-link text-danger p-0 text-decoration-none"
                                   onclick="return confirm('Yorumu silmek istediğinizden emin misiniz?')">
                                    <i class="bi bi-trash"></i> Sil
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Favori toggle
document.querySelectorAll('.fav-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const res = await fetch('api/toggle_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `eser_id=${id}`
        });
        const data = await res.json();
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        if (data.status === 'added') {
            btn.classList.replace('btn-outline-danger', 'btn-danger');
            icon.classList.replace('bi-heart', 'bi-heart-fill');
            span.textContent = ' Favorilerimde';
        } else {
            btn.classList.replace('btn-danger', 'btn-outline-danger');
            icon.classList.replace('bi-heart-fill', 'bi-heart');
            span.textContent = ' Favorilere Ekle';
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
