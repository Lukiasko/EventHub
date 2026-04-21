<section class="admin-page-header">
    <div>
        <p class="muted">Prehľad systému</p>
        <h1>Prehľad</h1>
    </div>
    <a class="btn" href="<?= url('admin_event_create') ?>">Pridať podujatie</a>
</section>

<section class="panel welcome-panel">
    <div>
        <p class="muted">Vitajte v administrácii</p>
        <h2>Dobrý deň, <?= e($adminUsername) ?></h2>
        <p>Tu môžete spravovať obsah portálu EventHub.</p>
    </div>
    <div class="dashboard-actions">
        <a href="<?= url('admin_events') ?>">Správa podujatí</a>
        <a href="<?= url('admin_categories') ?>">Správa kategórií</a>
        <a class="danger-link" href="<?= url('logout') ?>">Odhlásiť sa</a>
    </div>
</section>

<section class="stats-grid">
    <div class="stat-card">
        <span>Všetky podujatia</span>
        <strong><?= (int) $eventsCount ?></strong>
    </div>
    <div class="stat-card">
        <span>Nadchádzajúce</span>
        <strong><?= (int) $upcomingCount ?></strong>
    </div>
    <div class="stat-card">
        <span>Kategórie</span>
        <strong><?= (int) $categoriesCount ?></strong>
    </div>
    <div class="stat-card">
        <span>Správy</span>
        <strong><?= (int) $messagesCount ?></strong>
    </div>
</section>

<section class="admin-grid">
    <div class="panel">
        <div class="panel-heading">
            <h2>Najnovšie podujatia</h2>
            <a href="<?= url('admin_events') ?>">Spravovať</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Názov</th>
                        <th>Kategória</th>
                        <th>Dátum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latestEvents as $event): ?>
                        <tr>
                            <td><?= e($event['title']) ?></td>
                            <td><?= e($event['category_name']) ?></td>
                            <td><?= e(format_date($event['event_date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($latestEvents === []): ?>
                        <tr><td colspan="3">Zatiaľ neexistujú žiadne podujatia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <h2>Kontaktné správy</h2>
        </div>
        <?php if ($latestMessages === []): ?>
            <p class="muted">Zatiaľ neprišla žiadna správa.</p>
        <?php endif; ?>
        <?php foreach ($latestMessages as $message): ?>
            <article class="message-item">
                <strong><?= e($message['name']) ?></strong>
                <span><?= e($message['email']) ?> · <?= e(format_date($message['created_at'])) ?></span>
                <p><?= e(short_text($message['message'], 110)) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
