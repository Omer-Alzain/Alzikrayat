<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="gallery-header">
    <h1> Memories Gallery</h1>
    <p class="sub">Browsing through shared memories.</p>

    <div class="toolbar-row">
        <div class="layout-toggles">
            <button type="button" class="layout-btn active" data-layout="grid-3">3 Columns</button>
            <button type="button" class="layout-btn" data-layout="grid-4">4 Columns</button>
            <button type="button" class="layout-btn" data-layout="list">List</button>
            <button type="button" class="layout-btn" data-layout="slider">Slider</button>
        </div>
    </div>
</div>

<div class="card-grid grid-3" id="galleryGrid">
    <?php if (empty($photos)): ?>
        <p>No photos yet. <a href="/upload">Upload the first one.</a></p>
    <?php endif; ?>
    <?php foreach ($photos as $photo): ?>
        <a href="/photo/<?= $photo['id'] ?>" class="photo-card">
            <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" alt="<?= htmlspecialchars($photo['title']) ?>">
            <div class="body">
                <h3><?= htmlspecialchars($photo['title']) ?></h3>
                <p><?= htmlspecialchars($photo['description']) ?></p>
                <span class="date"><?= htmlspecialchars(date('M d, Y', strtotime($photo['date_time']))) ?></span>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>