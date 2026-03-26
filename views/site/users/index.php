<h2>Отображение пользователей</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label">Роль пользователя</label>
            <select class="form-select" name="choiced_role_id">
                <?php foreach ($can_view as $role): ?>
                    <option value="<?= $role ?>" <?php if ($current_role === $role): ?> selected <?php endif;?> ><?= \Models\Role::$roles[$role] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">ФИО пользователя (поиск по совпадениям)</label>
            <input class="form-control" name="search" type="text">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Поиск</button>
        </div>
    </form>
</div>
<hr>
<h3>Вы просматриваете: <?= htmlspecialchars(\Models\Role::$roles[$current_role]) ?></h3>

<?php if(isset($search_text)): ?>
<h4>Результаты запроса по "<b><?= $search_text ?></b>"</h4>
<?php endif; ?>

<div class="container mt-4">
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
        <tr>
            <th></th>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Email</th>
            <th>Телефон</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <tr class="td-all-text-centered">
                <td><a class="btn btn-outline-primary" href="<?= app()->route->getUrl('/users/show?id=' . (int) $user->id) ?>">-></a></td>
                <td><?= htmlspecialchars($user->id) ?></td>
                <td><?= htmlspecialchars($user->surname) ?></td>
                <td><?= htmlspecialchars($user->name) ?></td>
                <td><?= htmlspecialchars($user->patronymic ?? '-') ?></td>
                <td><?= htmlspecialchars($user->email) ?></td>
                <td><?= htmlspecialchars($user->phone) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <tr>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>