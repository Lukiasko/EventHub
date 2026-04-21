<section class="admin-page-header">
    <div>
        <p class="muted">Kategórie</p>
        <h1>Odstrániť kategóriu</h1>
    </div>
    <a class="btn btn-secondary" href="<?= url('admin_categories') ?>">Späť</a>
</section>

<form class="panel confirm-box" method="post" action="<?= url('admin_category_delete', ['id' => $category['id']]) ?>">
    <?= csrf_field() ?>
    <h2>Naozaj chcete odstrániť kategóriu?</h2>
    <p><strong><?= e($category['name']) ?></strong></p>

    <?php if ($eventsCount > 0): ?>
        <div class="alert alert-error">
            <p>Kategória obsahuje <?= (int) $eventsCount ?> podujatí, preto ju nie je možné odstrániť.</p>
        </div>
    <?php else: ?>
        <p>Táto akcia je trvalá a nebude ju možné vrátiť späť.</p>
    <?php endif; ?>

    <div class="form-actions">
        <a class="btn btn-secondary" href="<?= url('admin_categories') ?>">Zrušiť</a>
        <button class="btn btn-danger" type="submit" <?= $eventsCount > 0 ? 'disabled' : '' ?>>Odstrániť</button>
    </div>
</form>
