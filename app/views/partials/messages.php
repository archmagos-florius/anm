<?php foreach (consume_flash() as $message): ?>
    <article class="flash flash-<?= e($message['type']) ?>">
        <?= e($message['message']) ?>
    </article>
<?php endforeach; ?>
