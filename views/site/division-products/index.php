<?php if ($division): ?>
    <h2>Продукты подразделения: <?= htmlspecialchars($division->name) ?></h2>
    <hr>
    <h3><?= $message ?? ''; ?></h3>
    <br>

    <div class="container-sm">
        <h4>Добавить продукт в подразделение</h4>
        <form method="post" action="<?= app()->route->getUrl('/division-products/add') ?>" class="row g-3 align-items-center">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="col-md-8">
                <label for="product_id" class="visually-hidden">Продукт</label>
                <select class="form-select" name="product_id" id="product_id">
                    <option selected disabled>Выберите продукт для добавления...</option>
                    <?php foreach ($available_products as $product): ?>
                        <option value="<?= $product->id ?>">
                            <?= htmlspecialchars($product->name) ?> (Арт: <?= htmlspecialchars($product->articul) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </form>
    </div>
    <br>
    <hr>

    <h4>Продукты в наличии</h4>
    <div class="container mt-4">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-light">
            <tr>
                <th>Название</th>
                <th>Артикул</th>
                <th>Ед. изм.</th>
                <th>Количество</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr class="td-all-text-centered">
                        <td><?= htmlspecialchars($product->name) ?></td>
                        <td><?= htmlspecialchars($product->articul) ?></td>
                        <td><?= htmlspecialchars($product->unitType->name) ?></td>
                        <td class="d-flex align-items-center" style="gap: 10px;">
                            <form method="post" action="<?= app()->route->getUrl('/division-products/update-count') ?>" class="d-flex align-items-center" style="gap: 10px;">
                                <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                                <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                <input type="number" class="form-control form-control-sm" name="count" value="<?= $product->pivot->count ?>" min="0" style="width: 80px;">
                                <button class="btn btn-sm btn-success">✓</button>
                            </form>
                            <div>
                                <form method="post" action="<?= app()->route->getUrl('/division-products/remove') ?>">
                                    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите убрать продукт из подразделения?');">Убрать</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">В вашем подразделении пока нет продуктов.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <h2>Продукты подразделения</h2>
    <hr>
    <div class="alert alert-warning" role="alert">
        Вы не привязаны ни к одному подразделению. Обратитесь к администратору.
    </div>
<?php endif; ?>