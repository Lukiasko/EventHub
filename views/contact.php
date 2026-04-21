<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Kontakt</p>
        <h1>Napíš nám správu</h1>
        <p>Ak máš otázku k podujatiam alebo chceš pridať vlastnú udalosť, vyplň kontaktný formulár.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div class="contact-info">
            <h2>EventHub tím</h2>
            <p>Správy sa ukladajú do databázy a administrátor ich vidí na dashboarde.</p>
            <ul>
                <li>E-mail: info@eventhub.local</li>
                <li>Mesto: Bratislava</li>
                <li>Odpoveď zvyčajne do 2 pracovných dní</li>
            </ul>
        </div>

        <form class="form-panel" method="post" action="<?= url('contact') ?>">
            <?= csrf_field() ?>

            <?php if ($errors !== []): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= e($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label>
                Meno
                <input type="text" name="name" value="<?= e(old('name', $data)) ?>" required>
            </label>
            <label>
                E-mail
                <input type="email" name="email" value="<?= e(old('email', $data)) ?>" required>
            </label>
            <label>
                Správa
                <textarea name="message" rows="6" required><?= e(old('message', $data)) ?></textarea>
            </label>
            <button class="btn btn-primary" type="submit">Odoslať správu</button>
        </form>
    </div>
</section>
