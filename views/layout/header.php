<?php require_once __DIR__ . '/../../core/Session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alzikrayat</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="/" class="logo">
            <div class="logo-badge">A</div>
            <div class="logo-text">
                <span class="en">Alzikrayat</span>
                <span class="ar" dir="auto">الذكريات</span>
            </div>
        </a>

        <div class="nav-links">
            <a href="/">Home</a>
            <a href="/gallery">Gallery</a>
            <a href="/upload">Upload</a>
            <a href="/about">About</a>
        </div>

        <div class="nav-auth">
            <?php if (Session::isLoggedIn()): ?>
                <span class="status">Hi <?= htmlspecialchars($_SESSION['first_name']) ?></span>
                <a href="/auth/logout" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <span class="status">Please Login</span>
                <a href="/auth/login" class="btn btn-outline">Login</a>
                <a href="/auth/register" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>