<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Administrácia') ?> | EventHub</title>
    <link rel="stylesheet" href="<?= asset('public/css/admin.css') ?>">
</head>
<body>
    <aside class="admin-sidebar">
        <a class="admin-brand" href="<?= url('admin_dashboard') ?>">EventHub Administrácia</a>
        <nav>
            <a href="<?= url('admin_dashboard') ?>">Prehľad</a>
            <a href="<?= url('admin_events') ?>">Podujatia</a>
            <a href="<?= url('admin_categories') ?>">Kategórie</a>
            <a href="<?= url('home') ?>">Verejný web</a>
            <a href="<?= url('logout') ?>">Odhlásiť sa</a>
        </nav>
    </aside>

    <div class="admin-shell">
        <header class="admin-topbar">
            <div>
                <span class="muted">Prihlásený používateľ</span>
                <strong><?= e(Session::get('admin_username', 'admin')) ?></strong>
            </div>
        </header>

        <?php if ($success = Session::flash('success')): ?>
            <div class="flash flash-success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($error = Session::flash('error')): ?>
            <div class="flash flash-error"><?= e($error) ?></div>
        <?php endif; ?>

        <main class="admin-content">
