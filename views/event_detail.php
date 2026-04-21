<section class="detail-hero">
    <img src="<?= e(event_image($event['image'])) ?>" alt="<?= e($event['title']) ?>">
    <div class="detail-overlay">
        <div class="container">
            <span class="badge"><?= e($event['category_name']) ?></span>
            <h1><?= e($event['title']) ?></h1>
        </div>
    </div>
</section>

<section class="section">
    <div class="container detail-grid">
        <article class="detail-content">
            <h2>Popis podujatia</h2>
            <p><?= nl2br(e($event['description'])) ?></p>
        </article>
        <aside class="detail-info">
            <div>
                <span>Dátum a čas</span>
                <strong><?= e(format_date($event['event_date'])) ?></strong>
            </div>
            <div>
                <span>Miesto</span>
                <strong><?= e($event['location']) ?></strong>
            </div>
            <div>
                <span>Kategória</span>
                <strong><?= e($event['category_name']) ?></strong>
            </div>
            <a class="btn btn-primary" href="<?= url('contact') ?>">Mám otázku</a>
        </aside>
    </div>
</section>
