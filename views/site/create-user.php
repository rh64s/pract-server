<h2>Регистрация нового пользователя</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<form method="post">
    <div class="container">
        <form method="post">
            <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
            <div class="mb-3">
                <label class="form-label">Логин</label>
                <input class="form-control" type="text" name="login">
            </div>
            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input class="form-control" type="text" name="name">
            </div>
            <div class="mb-3">
                <label class="form-label">Фамилия</label>
                <input class="form-control" type="text" name="surname">
            </div>
            <div class="mb-3">
                <label class="form-label">Отчество</label>
                <input class="form-control" type="text" name="patronymic">
            </div>
            <div class="mb-3">
                <label class="form-label">Номер телефона</label>
                <input class="form-control" type="tel" name="phone">
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input class="form-control" type="email" name="email">
            </div>
            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input class="form-control" type="password" name="password">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Зарегистрировать</button>
            </div>
        </form>
    </div>
</form>
