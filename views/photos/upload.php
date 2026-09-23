<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="upload-page">
    <div class="upload-card">
        <h1>Share a New Memory</h1>
        <p class="sub">Contribute a photograph to the archive.</p>

        <?php if (!empty($errors['photo'])): ?>
            <p class="general-error"><?= htmlspecialchars($errors['photo'][0]) ?></p>
        <?php endif; ?>

        <form method="POST" action="/upload" enctype="multipart/form-data">
            <div class="form-fields">
                <div class="form-group">
                    <label for="title">Photo Title</label>
                    <input type="text" name="title" id="title" placeholder="e.g., Spring Graduation Picnic, 1994"
                           value="<?= htmlspecialchars($data['title'] ?? '') ?>">
                    <?php if (!empty($errors['title'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['title'][0]) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="optional">(Optional)</span></label>
                    <textarea name="description" id="description" style="height:120px"
                              placeholder="Share the story behind this photo..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Photo File</label>
                    <div class="dropzone">
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                        <p style="margin-top:12px;font-size:13px;">JPG, PNG or WEBP, up to 2MB</p>
                    </div>
                </div>
            </div>

            <br>
            <button type="submit" class="btn-submit">Upload Photo to Archive</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>