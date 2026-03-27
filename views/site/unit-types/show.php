<h2>Редактирование типа единицы: <?= htmlspecialchars($unit_type->name) ?></h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label" for="name">Название</label>
            <input class="form-control" id="name" type="text" name="name" value="<?= htmlspecialchars($unit_type->name) ?>">
        </div>

        <div class="d-flex justify-content-between">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Обновить</button>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-secondary" href="<?= app()->route->getUrl('/unit-types') ?>">Назад</a>
            </div>
        </div>
    </form>
    <hr>
    <form method="post" action="<?= app()->route->getUrl('/unit-types/delete?id=' . $unit_type->id) ?>">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="col-auto">
            <button type="submit" class="btn btn-danger mb-3" onclick="return confirm('Вы уверены, что хотите удалить этот тип?');">Удалить</button>
        </div>
    </form>
</div>