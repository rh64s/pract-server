<h2>Просмотр подраздела</h2>
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
                <label class="form-label">Название</label>
                <input class="form-control" type="text" name="name", value="<?= $division->name ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Сменить кладовщика, который будет содержать это подразделение</label>
                <select class="form-select" name="user_id">
                    <?php foreach ($storekeepers as $user): ?>
                        <option value="<?= $user->id ?>" <?php if($user->id === $division->user_id): ?> selected <?php endif; ?>>ID: <?=$user->id ?> | <?= $user->name ?> <?=$user->surname ?> <?=$user->patronymic ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Изменить</button>
            </div>
        </form>
        <form method="post" action="<?= app()->route->getUrl('/divisions/delete?id=' . $division->id) ?>">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="col-auto">
                <button type="submit" class="btn btn-danger mb-3" onclick="return confirm('Вы уверены, что хотите удалить этот подразделение?');">Удалить</button>
            </div>
        </form>
</div>
