<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('public/css/style.css') ?>">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container">
            <a class="brand" href="<?= url('home') ?>">EventHub</a>
            <button class="nav-toggle" type="button" aria-label="Otvoriť navigáciu">☰</button>
            <div class="nav-links">
                <a href="<?= url('home') ?>">Domov</a>
                <a href="<?= url('events') ?>">Podujatia</a>
                <a href="<?= url('contact') ?>">Kontakt</a>
                <?php if (Session::get('user_id')): ?>
                    <span class="nav-user"><?= e(Session::get('username', 'Používateľ')) ?></span>
                    <a href="<?= url('user_logout') ?>">Odhlásenie</a>
                <?php else: ?>
                    <a href="<?= url('register') ?>">Registrácia</a>
                    <a href="<?= url('user_login') ?>">Prihlásenie</a>
                <?php endif; ?>
                <?php if (Session::get('admin_id')): ?>
                    <a href="<?= url('admin_dashboard') ?>">Administrácia</a>
                    <a href="<?= url('logout') ?>">Odhlásenie</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <?php if ($success = Session::flash('success')): ?>
        <div class="flash flash-success container"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if ($error = Session::flash('error')): ?>
        <div class="flash flash-error container"><?= e($error) ?></div>
    <?php endif; ?>

    <main>
