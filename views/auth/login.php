<section class="login-section">
    <form class="login-card" method="post" action="<?= url('login') ?>">
        <?= csrf_field() ?>
        <p class="eyebrow">Administrácia</p>
        <h1>Prihlásenie</h1>
        <p>Prístup je určený iba pre správcu portálu EventHub.</p>

        <?php if ($errors !== []): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?= e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label>
            Používateľské meno
            <input type="text" name="username" value="<?= e($username) ?>" required autofocus>
        </label>
        <label>
            Heslo
            <input type="password" name="password" required>
        </label>
        <button class="btn btn-primary" type="submit">Prihlásiť sa</button>
        <p class="auth-switch">Nemáte používateľský účet? <a href="<?= url('register') ?>">Registrovať sa</a></p>
    </form>
</section>
