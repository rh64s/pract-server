<h2>Просмотр пользователя</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<?php
$csrf = app()->auth::generateCSRF();
?>
<div class="container">
    <div class="container">
        <form method="post">
            <input name="csrf_token" type="hidden" value="<?= $csrf; ?>"/>
            <div class="mb-3">
                <label class="form-label">Логин</label>
                <input class="form-control" type="text" name="login" value="<?= $division->login ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input class="form-control" type="text" name="name" value="<?= $division->name ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Фамилия</label>
                <input class="form-control" type="text" name="surname" value="<?= $division->surname ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Отчество</label>
                <input class="form-control" type="text" name="patronymic" value="<?= $division->patronymic ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Номер телефона</label>
                <input class="form-control" type="tel" name="phone" value="<?= $division->phone ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input class="form-control" type="email" name="email" value="<?= $division->email ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Роль</label>
                <select class="form-select" name="role_id">
                    <?php foreach ($can_role as $role): ?>
                        <option value="<?= $role ?>"><?= \Models\Role::$roles[$role] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Изменить</button>
            </div>
        </form>
        <form method="post" action="<?= app()->route->getUrl('/users/delete') ?>">
            <div class="col-auto">
                <input type="hidden" name="id" value="<?= $division->id ?>">
                <input name="csrf_token" type="hidden" value="<?= $csrf; ?>"/>
                <button type="submit" class="btn btn-outline-danger mb-3">Удалить</button>
            </div>
        </form>
</div>
