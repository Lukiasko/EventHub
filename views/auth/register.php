<section class="login-section">
    <form class="login-card" method="post" action="<?= url('register') ?>">
        <?= csrf_field() ?>
        <p class="eyebrow">Používateľský účet</p>
        <h1>Registrácia</h1>
        <p>Vytvorte si účet a získajte prístup k používateľskej časti EventHubu.</p>

        <?php if ($errors !== []): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?= e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label>
            Používateľské meno
            <input type="text" name="username" value="<?= e(old('username', $data)) ?>" required autofocus>
        </label>
        <label>
            Email
            <input type="email" name="email" value="<?= e(old('email', $data)) ?>" required>
        </label>
        <label>
            Heslo
            <input type="password" name="password" required>
        </label>
        <label>
            Potvrdiť heslo
            <input type="password" name="confirm_password" required>
        </label>
        <button class="btn btn-primary" type="submit">Registrovať sa</button>
        <p class="auth-switch">Už máte účet? <a href="<?= url('user_login') ?>">Prihlásiť sa</a></p>
    </form>
</section>
