<h1>Login</h1>
<?php foreach ($errors as $error): ?><article class="flash flash-error"><?= e($error) ?></article><?php endforeach; ?>
<form method="post">
    <?= csrf_field() ?>
    <label>Email <input type="email" name="email" value="<?= e(posted('email')) ?>" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Login</button>
</form>
