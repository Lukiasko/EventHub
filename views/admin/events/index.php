<section class="admin-page-header">
    <div>
        <p class="muted">Správa údajov</p>
        <h1>Podujatia</h1>
    </div>
    <a class="btn" href="<?= url('admin_event_create') ?>">Nové podujatie</a>
</section>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Názov</th>
                    <th>Kategória</th>
                    <th>Miesto</th>
                    <th>Dátum</th>
                    <th>Akcie</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= e($event['title']) ?></td>
                        <td><?= e($event['category_name']) ?></td>
                        <td><?= e($event['location']) ?></td>
                        <td><?= e(format_date($event['event_date'])) ?></td>
                        <td class="actions">
                            <a href="<?= url('event_detail', ['id' => $event['id']]) ?>">Detail</a>
                            <a href="<?= url('admin_event_edit', ['id' => $event['id']]) ?>">Upraviť</a>
                            <a class="danger" href="<?= url('admin_event_delete', ['id' => $event['id']]) ?>">Odstrániť</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($events === []): ?>
                    <tr><td colspan="5">Zatiaľ neexistujú žiadne podujatia.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
