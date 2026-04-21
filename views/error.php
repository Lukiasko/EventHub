<section class="section">
    <div class="container narrow">
        <div class="empty-state">
            <p class="eyebrow">Upozornenie</p>
            <h1><?= e($pageTitle ?? 'Chyba') ?></h1>
            <p><?= e($errorMessage ?? 'Požadovanú stránku sa nepodarilo zobraziť.') ?></p>
            <a class="btn btn-primary" href="<?= url('home') ?>">Späť na domov</a>
        </div>
    </div>
</section>
