<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/css/style.css">
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="logo">
                <div class="logo-badge">A</div>
            </div>
            <h1>Welcome Back</h1>
            <?php if (!empty($lastLogin)): ?>
                <p>Last login from this computer was <?= htmlspecialchars($lastLogin) ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($errors['general'])): ?>
            <p class="general-error"><?= htmlspecialchars($errors['general'][0]) ?></p>
        <?php endif; ?>

        <form method="POST" action="/auth/login">
            <div class="form-fields">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="e.g., student@university.edu"
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['email'][0]) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••••••">
                    <?php if (!empty($errors['password'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['password'][0]) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <br>
            <button type="submit" class="btn-submit">Login to Alzikrayat</button>
        </form>

        <p class="auth-switch">Don't have an account? <a href="/auth/register">Register here</a></p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>