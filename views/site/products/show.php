<h2>Редактирование продукта: <?= htmlspecialchars($productItem->name) ?></h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label" for="name">Название</label>
            <input class="form-control" id="name" type="text" name="name" value="<?= htmlspecialchars($productItem->name) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="articul">Артикул</label>
            <input class="form-control" id="articul" type="text" name="articul" value="<?= htmlspecialchars($productItem->articul) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="unit_type_id">Тип единицы</label>
            <select class="form-select" name="unit_type_id" id="unit_type_id">
                <?php foreach ($unit_types as $type): ?>
                    <option value="<?= $type->id ?>" <?php if ($type->id === $productItem->unit_type_id) echo 'selected'; ?>>
                        <?= htmlspecialchars($type->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Обновить</button>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-secondary" href="<?= app()->route->getUrl('/products') ?>">Назад</a>
            </div>
        </div>
    </form>
    <hr>
    <form method="post" action="<?= app()->route->getUrl('/products/delete?id=' . $productItem->id) ?>">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="col-auto">
            <button type="submit" class="btn btn-danger mb-3" onclick="return confirm('Вы уверены, что хотите удалить этот продукт?');">Удалить</button>
        </div>
    </form>
</div>