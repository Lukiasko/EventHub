<section class="admin-page-header">
    <div>
        <p class="muted">Podujatia</p>
        <h1>Odstrániť podujatie</h1>
    </div>
    <a class="btn btn-secondary" href="<?= url('admin_events') ?>">Späť</a>
</section>

<form class="panel confirm-box" method="post" action="<?= url('admin_event_delete', ['id' => $event['id']]) ?>">
    <?= csrf_field() ?>
    <h2>Naozaj chcete odstrániť podujatie?</h2>
    <p><strong><?= e($event['title']) ?></strong></p>
    <p>Táto akcia je trvalá a nebude ju možné vrátiť späť.</p>
    <div class="form-actions">
        <a class="btn btn-secondary" href="<?= url('admin_events') ?>">Zrušiť</a>
        <button class="btn btn-danger" type="submit">Odstrániť</button>
    </div>
</form>
