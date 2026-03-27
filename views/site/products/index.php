<h2>Продукты</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container-sm">
    <a class="btn btn-primary" href="<?= app()->route->getUrl('/products/create') ?>">Создать продукт</a>
</div>
<br>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label">Название или артикул (поиск по совпадениям)</label>
            <input class="form-control" name="search" type="text" value="<?= $search_text ?? '' ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Поиск</button>
        </div>
    </form>
</div>
<hr>

<?php if(isset($search_text)): ?>
    <h4>Результаты запроса по "<b><?= htmlspecialchars($search_text) ?></b>"</h4>
<?php endif; ?>

<div class="container mt-4">
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
        <tr>
            <th></th>
            <th>ID</th>
            <th>Название</th>
            <th>Артикул</th>
            <th>Тип единицы</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <tr class="td-all-text-centered">
                    <td><a class="btn btn-outline-primary" href="<?= app()->route->getUrl('/products/show?id=' . (int) $product->id) ?>">-></a></td>
                    <td><?= htmlspecialchars($product->id) ?></td>
                    <td><?= htmlspecialchars($product->name) ?></td>
                    <td><?= htmlspecialchars($product->articul) ?></td>
                    <td><?= htmlspecialchars($product->unitType->name) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>