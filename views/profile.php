<?php
$displayName = trim((string) ($user['nickname'] ?? ''));
if ($displayName === '') {
    $displayName = (string) $user['username'];
}
$avatarUrl = user_avatar($user['avatar']);
?>

<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Používateľ</p>
        <h1>Profil používateľa</h1>
        <p>Tu si upravíš prezývku, profilovku a uvidíš svoje prihlásené podujatia.</p>
    </div>
</section>

<section class="section">
    <div class="container profile-layout">
        <aside class="profile-card">
            <div class="profile-avatar">
                <img
                    data-avatar-preview
                    src="<?= e($avatarUrl !== '' ? $avatarUrl : '') ?>"
                    alt="Profilová fotka"
                    <?= $avatarUrl === '' ? 'hidden' : '' ?>
                >
            </div>

            <div class="profile-info">
                <h2><?= e($displayName) ?></h2>
                <div class="profile-info-list">
                    <div>
                        <span>Používateľské meno</span>
                        <strong><?= e($user['username']) ?></strong>
                    </div>
                    <div>
                        <span>Email</span>
                        <strong><?= e($user['email']) ?></strong>
                    </div>
                    <div>
                        <span>Registrovaný od</span>
                        <strong><?= e(format_date($user['created_at'])) ?></strong>
                    </div>
                </div>
            </div>
        </aside>

        <div class="profile-main">
            <form class="panel form-grid" method="post" action="<?= url('profile') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <h2>Úprava profilu</h2>

                <?php if ($errors !== []): ?>
                    <div class="alert alert-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= e($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <label>
                    Prezývka
                    <input type="text" name="nickname" value="<?= e(old('nickname', $user)) ?>" required>
                </label>

                <label>
                    Profilová fotka
                    <input data-avatar-input type="file" name="avatar" accept="image/png,image/jpeg,image/gif,image/webp">
                </label>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Uložiť profil</button>
                    <a class="btn btn-secondary" href="<?= url('events') ?>">Pozrieť podujatia</a>
                </div>
            </form>

            <section class="panel">
                <div class="panel-heading">
                    <h2>Prihlásené podujatia</h2>
                    <span><?= count($registeredEvents) ?> položiek</span>
                </div>

                <?php if ($registeredEvents === []): ?>
                    <div class="empty-state">
                        <h3>Zatiaľ nie ste prihlásený na žiadne podujatie</h3>
                        <p>Vyberte si podujatie a prihláste sa bez platenia.</p>
                    </div>
                <?php else: ?>
                    <div class="registered-list">
                        <?php foreach ($registeredEvents as $registeredEvent): ?>
                            <article class="registered-item">
                                <div>
                                    <span class="badge"><?= e($registeredEvent['category_name'] ?? 'Podujatie') ?></span>
                                    <h3><?= e($registeredEvent['title']) ?></h3>
                                    <p><?= e($registeredEvent['location']) ?> · <?= e(format_date($registeredEvent['event_date'])) ?></p>
                                    <small>Prihlásený od <?= e(format_date($registeredEvent['registered_at'])) ?></small>
                                </div>
                                <div class="registered-actions">
                                    <?php
                                    // use PHP's default timezone (local) for start/end
                                    $tzName = date_default_timezone_get();
                                    try {
                                        $tz = new DateTimeZone($tzName);
                                    } catch (Exception $e) {
                                        $tz = new DateTimeZone('UTC');
                                        $tzName = 'UTC';
                                    }

                                    try {
                                        $start = new DateTimeImmutable($registeredEvent['event_date'], $tz);
                                    } catch (Exception $e) {
                                        $start = new DateTimeImmutable('now', $tz);
                                    }

                                    $end = $start->modify('+2 hours'); // predvolená dĺžka 2 hodiny

                                    $dtstart = $start->format('Ymd\\THis');
                                    $dtend = $end->format('Ymd\\THis');
                                    $gdates = $dtstart . '/' . $dtend;

                                    $text = urlencode((string) ($registeredEvent['title'] ?? 'Podujatie'));
                                    $details = urlencode((string) ($registeredEvent['description'] ?? ''));
                                    $location = urlencode((string) ($registeredEvent['location'] ?? ''));
                                    $gcalUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
                                        . '&text=' . $text
                                        . '&dates=' . $gdates
                                        . '&details=' . $details
                                        . '&location=' . $location
                                        . '&ctz=' . urlencode($tzName);
                                    ?>
                                    <a class="btn btn-primary" href="<?= $gcalUrl ?>" target="_blank" rel="noopener noreferrer">Pridať do kalendára</a>
                                    <form method="post" action="<?= url('event_unregister', ['id' => $registeredEvent['id']]) ?>">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-secondary" type="submit">Odhlásiť</button>
                                    </form>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>
