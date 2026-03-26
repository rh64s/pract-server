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
            <th>Дата создания</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr class="td-all-text-centered">
                    <td><?= htmlspecialchars($order->id) ?></td>
                    <td><?= htmlspecialchars($order->product->name) ?></td>
                    <td><?= htmlspecialchars($order->product->articul) ?></td>
                    <td><?= htmlspecialchars($order->count) ?></td>
                    <td><?= htmlspecialchars($order->product->unitType->name) ?></td>
                    <?php if (app()->auth::user()->isAdmin()): ?>
                        <td><?= htmlspecialchars($order->division->name) ?></td>
                    <?php endif; ?>
                    <td><?= date('d.m.Y H:i', strtotime($order->created_at)) ?></td>
                    <td>
                        <form method="post" action="<?= app()->route->getUrl('/orders/delete?id=' . $order->id) ?>">
                            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
                            <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?');">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>