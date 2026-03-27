<h2>Отчеты по подразделениям</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>

<?php if (app()->auth::user()->isAdmin()): ?>
    <div class="container mt-4">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-light">
            <tr>
                <th>Название подразделения</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($divisions)): ?>
                <?php foreach ($divisions as $division): ?>
                    <tr class="td-all-text-centered">
                        <td><?= htmlspecialchars($division->name) ?></td>
                        <td>
                            <a href="<?= app()->route->getUrl('/reports/show?id=' . $division->id) ?>" class="btn btn-primary">Посмотреть отчет</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">Подразделений пока нет.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
