<section class="admin-page-header">
    <div>
        <p class="muted">Prehľad používateľských prihlásení</p>
        <h1>Prihlásenia na podujatia</h1>
    </div>
    <a class="btn" href="<?= url('admin_dashboard') ?>">Späť na prehľad</a>
</section>

<section class="panel">
    <div class="panel-heading">
        <h2>Zoznam prihlásení</h2>
        <span><?= count($registrations) ?> záznamov</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Podujatie</th>
                    <th>Kategória</th>
                    <th>Používateľ</th>
                    <th>Email</th>
                    <th>Prihlásený od</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td>
                            <strong><?= e($registration['event_title']) ?></strong><br>
                            <span class="muted"><?= e(format_date($registration['event_date'])) ?></span>
                        </td>
                        <td><?= e($registration['category_name'] ?? 'Bez kategórie') ?></td>
                        <td>
                            <?= e($registration['nickname'] !== null && trim((string) $registration['nickname']) !== ''
                                ? $registration['nickname']
                                : $registration['username']) ?>
                        </td>
                        <td><?= e($registration['email']) ?></td>
                        <td><?= e(format_date($registration['registered_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if ($registrations === []): ?>
                    <tr>
                        <td colspan="5">Zatiaľ nie sú evidované žiadne prihlásenia na podujatia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
