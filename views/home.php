<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">EventHub</p>
            <h1>Objav najzaujímavejšie podujatia na jednom mieste</h1>
            <p>Koncerty, workshopy, prednášky aj komunitné stretnutia nájdeš prehľadne v jednom modernom portáli.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= url('events') ?>">Zobraziť podujatia</a>
                <a class="btn btn-secondary" href="<?= url('contact') ?>">Kontaktovať nás</a>
            </div>
        </div>
        <div class="hero-panel" aria-label="Najbližšie podujatie">
            <div class="hero-card">
                <span>Najbližšie</span>
                <strong><?= isset($events[0]) ? e($events[0]['title']) : 'Nové podujatia už čoskoro' ?></strong>
                <small><?= isset($events[0]) ? e(format_date($events[0]['event_date'])) : 'Sledujte aktuálny program' ?></small>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container section-heading">
        <div>
            <p class="eyebrow">Program</p>
            <h2>Odporúčané nadchádzajúce podujatia</h2>
        </div>
        <a class="text-link" href="<?= url('events') ?>">Všetky podujatia</a>
    </div>

    <div class="container card-grid">
        <?php if ($events === []): ?>
            <div class="empty-state full-width">
                <h3>Zatiaľ nie sú dostupné žiadne podujatia</h3>
                <p>Administrátor môže pridať nové podujatia v administračnej časti.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($events as $event): ?>
            <article class="event-card">
                <img src="<?= e(event_image($event['image'])) ?>" alt="<?= e($event['title']) ?>">
                <div class="event-card-body">
                    <span class="badge"><?= e($event['category_name']) ?></span>
                    <h3><?= e($event['title']) ?></h3>
                    <p><?= e(short_text($event['description'])) ?></p>
                    <div class="event-meta">
                        <span><?= e(format_date($event['event_date'])) ?></span>
                        <span><?= e($event['location']) ?></span>
                    </div>
                    <a class="text-link" href="<?= url('event_detail', ['id' => $event['id']]) ?>">Detail podujatia</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section section-muted">
    <div class="container section-heading">
        <div>
            <p class="eyebrow">Kategórie</p>
            <h2>Vyber si typ programu</h2>
        </div>
    </div>
    <div class="container category-grid">
        <?php foreach ($categories as $category): ?>
            <a class="category-tile" href="<?= url('events', ['category' => $category['id']]) ?>">
                <strong><?= e($category['name']) ?></strong>
                <span><?= (int) $category['events_count'] ?> podujatí</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="container about-band">
        <div>
            <p class="eyebrow">O EventHube</p>
            <h2>Jednoduchá správa podujatí v čistom PHP</h2>
        </div>
        <p>EventHub spája verejný katalóg podujatí s administračnou časťou pre správu kategórií a udalostí. Aplikácia používa objektovo orientované PHP, PDO, sessions a bezpečné ukladanie hesiel.</p>
    </div>
</section>
