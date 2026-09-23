<?php require __DIR__ . '/layout/header.php'; ?>

<div class="hero">
    <h1>Alzikrayat</h1>
    <p class="tagline">Share Your Memories</p>
    <div class="cta-row">
        <a href="/gallery" class="btn btn-hero-primary">Explore Archives</a>
        <a href="/auth/register" class="btn btn-hero-outline">Join Community</a>
    </div>
</div>

<div class="section-header">
    <div>
        <h2>Recent Memories</h2>
        <p>A preview of what the community has shared so far</p>
    </div>
    <a href="/gallery">View Full Gallery &rarr;</a>
</div>

<div class="card-grid" style="grid-template-columns: repeat(<?= max(count($previewPhotos), 1) ?>, 1fr);">
    <?php if (empty($previewPhotos)): ?>
        <p>No photos have been uploaded yet — <a href="/upload">be the first to share one.</a></p>
    <?php endif; ?>
    <?php foreach ($previewPhotos as $photo): ?>
        <a href="/photo/<?= $photo['id'] ?>" class="photo-card">
            <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" alt="<?= htmlspecialchars($photo['title']) ?>">
            <div class="body">
                <h3><?= htmlspecialchars($photo['title']) ?></h3>
                <span class="date"><?= htmlspecialchars(date('M d, Y', strtotime($photo['date_time']))) ?></span>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>