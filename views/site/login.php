<h2>Авторизация</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<h3><?= app()->auth->user()->name ?? ''; ?></h3>
<?php
if (!app()->auth::check()):
    ?>
    <div class="container">
        <form method="post">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="mb-3">
                <label class="form-label">Логин</label>
                <input class="form-control" type="text" name="login">
            </div>
            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input class="form-control" type="password" name="password">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Войти</button>
            </div>
        </form>
    </div>

<?php endif;