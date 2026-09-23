<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="detail-layout">
    <div class="detail-main">
        <img src="/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>" alt="<?= htmlspecialchars($photo['title']) ?>">

        <div class="comments-section">
            <h2>Comments (<?= count($comments) ?>)</h2>

            <?php foreach ($comments as $comment): ?>
                <div class="comment-item">
                    <div class="avatar"><?= htmlspecialchars(strtoupper(substr($comment['first_name'], 0, 1))) ?></div>
                    <div>
                        <div class="name-row">
                            <span class="name"><?= htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']) ?></span>
                            <span class="date"><?= htmlspecialchars(date('M d, Y', strtotime($comment['date_time']))) ?></span>
                        </div>
                        <p><?= htmlspecialchars($comment['comment']) ?></p>

                        <?php if (Session::isLoggedIn() && Session::getCurrentUserId() == $comment['user_id']): ?>
                            <form method="POST" action="/comment/<?= $comment['id'] ?>/delete/<?= $photo['id'] ?>"
                                  onsubmit="return confirm('Delete this comment?');" style="display:inline;">
                                <button type="submit" style="background:none;border:none;color:#b3261e;font-size:12px;cursor:pointer;padding:0;">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (Session::isLoggedIn()): ?>
                <form class="comment-form" method="POST" action="/comment" style="margin-top:20px;">
                    <input type="hidden" name="photo_id" value="<?= $photo['id'] ?>">
                    <textarea name="comment" placeholder="Write a comment..." required></textarea>
                    <button type="submit" class="btn-submit" style="width:auto;padding:10px 24px;">Post Comment</button>
                </form>
            <?php else: ?>
                <p><a href="/auth/login">Log in</a> to leave a comment.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="detail-sidebar">
        <h1><?= htmlspecialchars($photo['title']) ?></h1>
        <hr>
        <p class="description"><?= nl2br(htmlspecialchars($photo['description'])) ?></p>
        <p style="color: var(--text-light); font-size: 13px;">Uploaded <?= htmlspecialchars(date('M d, Y', strtotime($photo['date_time']))) ?></p>

        <?php if (Session::isLoggedIn() && Session::getCurrentUserId() == $photo['user_id']): ?>
            <hr>
            <form method="POST" action="/photo/<?= $photo['id'] ?>/delete" onsubmit="return confirm('Delete this photo permanently?');">
                <button type="submit" class="btn-delete">Delete This Photo</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>