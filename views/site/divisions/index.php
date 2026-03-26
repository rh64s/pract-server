<h2>Отображение подразделений</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container-sm">
    <a class="btn btn-primary" href="<?= app()->route->getUrl('/divisions/create') ?>">Создание раздела</a>
</div>
<br>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label">Название подразделения (поиск по совпадениям)</label>
            <input class="form-control" name="search" type="text">
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
            <th>ID кладовщика</th>
            <th>Имя кладовщика</th>
            <th>Фамилия кладовщика</th>
            <th>Отчество кладовщика</th>
            <th>Телефон кладовщика</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($divisions)): ?>
        <?php foreach ($divisions as $division): ?>
            <tr class="td-all-text-centered">
                <td><a class="btn btn-outline-primary" href="<?= app()->route->getUrl('/divisions/show?id=' . (int) $division->id) ?>">-></a></td>
                <td><?= htmlspecialchars($division->id) ?></td>
                <td><?= htmlspecialchars($division->name) ?></td>
                <td><?= htmlspecialchars($division->user->id) ?></td>
                <td><?= htmlspecialchars($division->user->name) ?></td>
                <td><?= htmlspecialchars($division->user->surname) ?></td>
                <td><?= htmlspecialchars($division->user->patronymic ?? '-') ?></td>
                <td><?= htmlspecialchars($division->user->phone) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>