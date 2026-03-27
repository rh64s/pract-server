<h2>Создание типа единицы</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <form method="post">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label class="form-label" for="name">Название</label>
            <input class="form-control" id="name" type="text" name="name" placeholder="Например, кг, шт, л">
        </div>

        <div class="d-flex justify-content-between">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Создать</button>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-danger" href="<?= app()->route->getUrl('/unit-types') ?>">Отмена</a>
            </div>
        </div>
    </form>
</div>