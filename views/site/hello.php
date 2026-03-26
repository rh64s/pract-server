<?php
$user = \Src\Auth\Auth::user();
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Профиль</h2>
            <hr>
            <p><strong>Имя:</strong> <?= $user->name ?></p>
            <p><strong>Фамилия:</strong> <?= $user->surname ?></p>
            <p><strong>Отчество:</strong> <?= $user->patronymic ?></p>
            <a href="<?= app()->route->getUrl('/users/set-avatar') ?>" class="btn btn-primary">Сменить аватар</a>
        </div>
        <div class="col-md-6">
            <div class="avatar-container">
                <img src="<?= $user->avatar ? '/' . $user->avatar : '/pop-it-mvc/public/static/img/default-avatar.png' ?>" alt="Аватар" class="avatar">
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-container {
        margin-bottom: 20px;
    }
    .avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
