<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="/css/style.css">
<div class="auth-page">
    <div class="auth-card wide">
        <div class="auth-card-header">
            <div class="logo">
                <div class="logo-badge">A</div>
            </div>
            <h1>Create Account</h1>
            <p>Join the academic memory archival project</p>
        </div>

        <?php if (!empty($errors['general'])): ?>
            <p class="general-error"><?= htmlspecialchars($errors['general'][0]) ?></p>
        <?php endif; ?>

        <form method="POST" action="/auth/register">
            <div class="form-fields">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" placeholder="e.g., Ahmed"
                               value="<?= htmlspecialchars($data['first_name'] ?? '') ?>">
                        <?php if (!empty($errors['first_name'])): ?>
                            <span class="error-text"><?= htmlspecialchars($errors['first_name'][0]) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" placeholder="e.g., Al-Mansoori"
                               value="<?= htmlspecialchars($data['last_name'] ?? '') ?>">
                        <?php if (!empty($errors['last_name'])): ?>
                            <span class="error-text"><?= htmlspecialchars($errors['last_name'][0]) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="your.name@university.edu"
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['email'][0]) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••">
                        <?php if (!empty($errors['password'])): ?>
                            <span class="error-text"><?= htmlspecialchars($errors['password'][0]) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••">
                        <?php if (!empty($errors['confirm_password'])): ?>
                            <span class="error-text"><?= htmlspecialchars($errors['confirm_password'][0]) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location <span class="optional">(Optional)</span></label>
                        <input type="text" name="location" id="location" placeholder="e.g., Abu Dhabi, UAE"
                               value="<?= htmlspecialchars($data['location'] ?? '') ?>">
                        <?php if (!empty($errors['location'])): ?>
                            <span class="error-text"><?= htmlspecialchars($errors['location'][0]) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="occupation">Occupation <span class="optional">(Optional)</span></label>
                        <input type="text" name="occupation" id="occupation" placeholder="e.g., Class of '18 Alumni"
                               value="<?= htmlspecialchars($data['occupation'] ?? '') ?>">
                        <?php if (!empty($errors['occupation'])): ?>
                            <span class="error-text"><?= htmlspecialchars($errors['occupation'][0]) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Short Bio <span class="optional">(Optional)</span></label>
                    <textarea name="description" id="description" placeholder="Tell us a bit about your university journey, memories, or your main area of focus..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['description'][0]) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <br>
            <button type="submit" class="btn-submit">Create Account &amp; Join Archiving</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="/auth/login">Login here</a></p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>