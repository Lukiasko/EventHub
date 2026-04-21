<section class="admin-page-header">
    <div>
        <p class="muted">Kategórie</p>
        <h1>Nová kategória</h1>
    </div>
    <a class="btn btn-secondary" href="<?= url('admin_categories') ?>">Späť</a>
</section>

<form class="panel form-grid" method="post" action="<?= url('admin_category_create') ?>">
    <?php require APP_ROOT . '/views/admin/categories/_form.php'; ?>
    <div class="form-actions">
        <button class="btn" type="submit">Uložiť kategóriu</button>
    </div>
</form>
