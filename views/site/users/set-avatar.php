<?php
$user = \Src\Auth\Auth::user();
?>
<h2>Смена аватара</h2>
<hr>
<h3><?= $message ?? ''; ?></h3>
<br>
<div class="container">
    <div class="avatar-container">
        <img class="avatar" src="<?= $user->avatar ? '/' . $user->avatar : '/pop-it-mvc/public/static/img/default-avatar.png' ?>" alt="Аватар">
    </div>
    <form method="post" enctype="multipart/form-data">
        <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
        <div class="mb-3">
            <label for="avatar" class="form-label">Выберите изображение</label>
            <input class="form-control" type="file" id="avatar" name="avatar">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Загрузить</button>
        </div>
    </form>
</div>