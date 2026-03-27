<h2>Отчет по подразделению: <?= htmlspecialchars($division->name) ?></h2>
<hr>
<br>

<div class="container mt-4">
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-light">
        <tr>
            <th>Продукт</th>
            <th>Количество</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr class="td-all-text-centered">
                    <td><?= htmlspecialchars($order->product->name) ?></td>
                    <td><?= htmlspecialchars($order->count) ?></td>
                    <td><?= $order->is_completed ? 'Выполнен' : 'В ожидании' ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Для этого подразделения пока нет заказов.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
