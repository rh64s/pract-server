<h2>Создание заказа</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>

        <div class="mb-3">
            <label class="form-label" for="division">Подразделение</label>
            <input class="form-control" id="division" type="text" value="<?= htmlspecialchars(app()->auth::user()->division->name) ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label" for="product_id">Продукт</label>
            <select class="form-select" name="product_id" id="product_id">
                <option selected disabled>Выберите продукт...</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product->id ?>"><?= htmlspecialchars($product->name) ?> (Арт: <?= htmlspecialchars($product->articul) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label" for="count">Количество</label>
            <input class="form-control" id="count" type="number" name="count" min="1" placeholder="Введите количество">
        </div>

        <div class="d-flex justify-content-between">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Создать заказ</button>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-danger" href="<?= app()->route->getUrl('/orders') ?>">Отмена</a>
            </div>
        </div>
    </form>
</div>