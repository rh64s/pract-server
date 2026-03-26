<h2>Создание продукта</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label" for="name">Название</label>
            <input class="form-control" id="name" type="text" name="name" placeholder="Например, Молоко">
        </div>
        <div class="mb-3">
            <label class="form-label" for="articul">Артикул</label>
            <input class="form-control" id="articul" type="text" name="articul" placeholder="Например, MLK-32">
        </div>
        <div class="mb-3">
            <label class="form-label" for="unit_type_id">Тип единицы</label>
            <select class="form-select" name="unit_type_id" id="unit_type_id">
                <option selected disabled>Выберите тип...</option>
                <?php foreach ($unit_types as $type): ?>
                    <option value="<?= $type->id ?>"><?= htmlspecialchars($type->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Создать</button>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-danger" href="<?= app()->route->getUrl('/products') ?>">Отмена</a>
            </div>
        </div>
    </form>
</div>