<h2>Типы единиц измерения</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container-sm">
    <a class="btn btn-primary" href="<?= app()->route->getUrl('/unit-types/create') ?>">Создать тип единицы</a>
</div>
<br>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label">Название (поиск по совпадениям)</label>
            <input class="form-control" name="search" type="text" value="<?= $search_text ?? '' ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Поиск</button>
        </div>
    </form>
</div>
<hr>

<?php if(isset($search_text)): ?>
    <h4>Результаты запроса по "<b><?= $search_text ?></b>"</h4>
<?php endif; ?>

<div class="container mt-4">
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
        <tr>
            <th></th>
            <th>ID</th>
            <th>Название</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($unit_types)): ?>
            <?php foreach ($unit_types as $type): ?>
                <tr class="td-all-text-centered">
                    <td><a class="btn btn-outline-primary" href="<?= app()->route->getUrl('/unit-types/show?id=' . (int) $type->id) ?>">-></a></td>
                    <td><?= htmlspecialchars($type->id) ?></td>
                    <td><?= htmlspecialchars($type->name) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>