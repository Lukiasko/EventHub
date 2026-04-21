<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Podujatia</p>
        <h1>Aktuálny program</h1>
        <p>Prehľad pripravovaných aj uložených podujatí s možnosťou filtrovania podľa kategórie.</p>
    </div>
</section>

<section class="section">
    <div class="container filter-bar">
        <a class="<?= $selectedCategory === null ? 'active' : '' ?>" href="<?= url('events') ?>">Všetky</a>
        <?php foreach ($categories as $category): ?>
            <a class="<?= $selectedCategory === (int) $category['id'] ? 'active' : '' ?>" href="<?= url('events', ['category' => $category['id']]) ?>">
                <?= e($category['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="container card-grid">
        <?php if ($events === []): ?>
            <div class="empty-state full-width">
                <h2>V tejto kategórii nie sú žiadne podujatia</h2>
                <p>Skúste zobraziť všetky podujatia alebo sa vráťte neskôr.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($events as $event): ?>
            <article class="event-card">
                <img src="<?= e(event_image($event['image'])) ?>" alt="<?= e($event['title']) ?>">
                <div class="event-card-body">
                    <span class="badge"><?= e($event['category_name']) ?></span>
                    <h2><?= e($event['title']) ?></h2>
                    <p><?= e(short_text($event['description'], 170)) ?></p>
                    <div class="event-meta">
                        <span><?= e(format_date($event['event_date'])) ?></span>
                        <span><?= e($event['location']) ?></span>
                    </div>
                    <a class="btn btn-small" href="<?= url('event_detail', ['id' => $event['id']]) ?>">Zobraziť detail</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
