<section class="login-section">
    <form class="login-card" method="post" action="<?= url('user_login') ?>">
        <?= csrf_field() ?>
        <p class="eyebrow">Používateľský účet</p>
        <h1>Prihlásenie</h1>
        <p>Prihláste sa pomocou emailu alebo používateľského mena.</p>

        <?php if ($errors !== []): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?= e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label>
            Email alebo meno
            <input type="text" name="login" value="<?= e($login) ?>" required autofocus>
        </label>
        <label>
            Heslo
            <input type="password" name="password" required>
        </label>
        <button class="btn btn-primary" type="submit">Prihlásiť sa</button>
        <p class="auth-switch">Nemáte účet? <a href="<?= url('register') ?>">Registrovať sa</a></p>
    </form>
</section>
