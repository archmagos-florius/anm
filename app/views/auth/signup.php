<h1>Sign up</h1>
<?php foreach ($errors as $error): ?><article class="flash flash-error"><?= e($error) ?></article><?php endforeach; ?>
<form method="post">
    <?= csrf_field() ?>
    <label>Name <input name="name" value="<?= e(posted('name')) ?>" required></label>
    <label>Email <input type="email" name="email" value="<?= e(posted('email')) ?>" required></label>
    <label>Phone <input name="phone" value="<?= e(posted('phone')) ?>"></label>
    <label>Password <input type="password" name="password" minlength="8" required></label>
    <button type="submit">Create account</button>
</form>
