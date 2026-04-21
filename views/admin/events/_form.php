<?php
$dateValue = old('event_date', $event);
if ($dateValue !== '') {
    $dateValue = date('Y-m-d\TH:i', strtotime($dateValue));
}
?>

<?php if ($errors !== []): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <p><?= e($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= csrf_field() ?>

<label>
    Názov
    <input type="text" name="title" value="<?= e(old('title', $event)) ?>" required>
</label>

<label>
    Kategória
    <select name="category_id" required>
        <option value="">Vyberte kategóriu</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= (int) $category['id'] ?>" <?= (string) old('category_id', $event) === (string) $category['id'] ? 'selected' : '' ?>>
                <?= e($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</label>

<label>
    Miesto
    <input type="text" name="location" value="<?= e(old('location', $event)) ?>" required>
</label>

<label>
    Dátum podujatia
    <input type="datetime-local" name="event_date" value="<?= e($dateValue) ?>" required>
</label>

<label>
    Obrázok
    <input type="text" name="image" value="<?= e(old('image', $event)) ?>" placeholder="URL obrázka alebo názov súboru v public/uploads">
</label>

<label>
    Popis
    <textarea name="description" rows="8" required><?= e(old('description', $event)) ?></textarea>
</label>
