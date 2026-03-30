<h2>Заказы</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<?php if (app()->auth::user()->isStorekeeper()): ?>
<div class="container-sm">
    <a class="btn btn-primary" href="<?= app()->route->getUrl('/orders/create') ?>">Создать заказ</a>
</div>
<br>
<?php endif; ?>

<div class="container mt-4">
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Продукт</th>
            <th>Артикул</th>
            <th>Количество</th>
            <th>Ед. изм.</th>
            <?php if (app()->auth::user()->isAdmin()): ?>
                <th>Подразделение</th>
            <?php endif; ?>
            <th>Статус</th>
            <th>Дата создания</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr class="td-all-text-centered <?= $order->is_completed ? 'table-success' : '' ?>">
                    <td><?= htmlspecialchars($order->id) ?></td>
                    <td><?= htmlspecialchars($order->product->name) ?></td>
                    <td><?= htmlspecialchars($order->product->articul) ?></td>
                    <td><?= htmlspecialchars($order->count) ?></td>
                    <td><?= htmlspecialchars($order->product->unitType->name) ?></td>
                    <?php if (app()->auth::user()->isAdmin()): ?>
                        <td><?= htmlspecialchars($order->division->name) ?></td>
                    <?php endif; ?>
                    <td><?= $order->is_completed ? 'Выполнен' : 'В ожидании' ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></td>
                    <td>
                        <?php if (app()->auth::user()->isAdmin() && !$order->is_completed): ?>
                            <form method="post" action="<?= app()->route->getUrl('/orders/complete') ?>">
                                <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                                <input type="hidden" name="id" value="<?= $order->id ?>">
                                <button class="btn btn-success" onclick="return confirm('Вы уверены, что хотите отметить этот заказ как принятый?');">Закончить</button>
                            </form>
                        <?php elseif (app()->auth::user()->isStorekeeper()): ?>
                        <form method="post" action="<?= app()->route->getUrl('/orders/delete') ?>">
                            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                            <input type="hidden" name="id" value="<?= $order->id ?>">
                            <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?');">Удалить</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">Заказов пока нет.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>