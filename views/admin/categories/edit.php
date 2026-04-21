<section class="admin-page-header">
    <div>
        <p class="muted">Kategórie</p>
        <h1>Upraviť kategóriu</h1>
    </div>
    <a class="btn btn-secondary" href="<?= url('admin_categories') ?>">Späť</a>
</section>

<form class="panel form-grid" method="post" action="<?= url('admin_category_edit', ['id' => $category['id']]) ?>">
    <?php require APP_ROOT . '/views/admin/categories/_form.php'; ?>
    <div class="form-actions">
        <button class="btn" type="submit">Uložiť zmeny</button>
    </div>
</form>
