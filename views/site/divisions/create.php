<h2>Регистрация нового подраздела</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label">Название</label>
            <input class="form-control" type="text" name="name">
        </div>
        <div class="mb-3">
            <label class="form-label">Кладовщик, который будет содержать это подразделение</label>
            <select class="form-select" name="user_id">
                <?php foreach ($storekeepers as $user): ?>
                    <option value="<?= $user->id ?>">ID: <?=$user->id ?> | <?= $user->name ?> <?=$user->surname ?> <?=$user->patronymic ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Зарегистрировать</button>
        </div>
    </form>
</div>
