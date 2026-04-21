<section class="admin-page-header">
    <div>
        <p class="muted">Správa údajov</p>
        <h1>Kategórie</h1>
    </div>
    <a class="btn" href="<?= url('admin_category_create') ?>">Nová kategória</a>
</section>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Názov</th>
                    <th>Počet podujatí</th>
                    <th>Vytvorená</th>
                    <th>Akcie</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= e($category['name']) ?></td>
                        <td><?= (int) $category['events_count'] ?></td>
                        <td><?= e(format_date($category['created_at'])) ?></td>
                        <td class="actions">
                            <a href="<?= url('admin_category_edit', ['id' => $category['id']]) ?>">Upraviť</a>
                            <a class="danger" href="<?= url('admin_category_delete', ['id' => $category['id']]) ?>">Odstrániť</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($categories === []): ?>
                    <tr><td colspan="4">Zatiaľ neexistujú žiadne kategórie.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
