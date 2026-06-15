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
        <form class="search-form" method="get" action="<?= BASE_URL ?>">
            <input type="hidden" name="page" value="events">
            <?php if ($selectedCategory !== null): ?>
                <input type="hidden" name="category" value="<?= (int) $selectedCategory ?>">
            <?php endif; ?>
            <input type="search" name="q" value="<?= e($search ?? '') ?>" placeholder="Vyhľadať podľa názvu..." aria-label="Vyhľadať podujatie">
            <button type="submit" class="btn-search" aria-label="Vyhľadať">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/>
                    <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </form>
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
