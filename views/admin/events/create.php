<section class="admin-page-header">
    <div>
        <p class="muted">Podujatia</p>
        <h1>Nové podujatie</h1>
    </div>
    <a class="btn btn-secondary" href="<?= url('admin_events') ?>">Späť</a>
</section>

<form class="panel form-grid" method="post" action="<?= url('admin_event_create') ?>">
    <?php require APP_ROOT . '/views/admin/events/_form.php'; ?>
    <div class="form-actions">
        <button class="btn" type="submit">Uložiť podujatie</button>
    </div>
</form>
