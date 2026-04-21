<?php if ($errors !== []): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $error): ?>
            <p><?= e($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= csrf_field() ?>

<label>
    Názov kategórie
    <input type="text" name="name" value="<?= e(old('name', $category)) ?>" required>
</label>
