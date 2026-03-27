<?php
$user = \Src\Auth\Auth::user();
?>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <?php if (\Src\Auth\Auth::check()): ?>
            <h2>Профиль</h2>
            <hr>
            <p><strong>Имя:</strong> <?= $user->name ?></p>
            <p><strong>Фамилия:</strong> <?= $user->surname ?></p>
            <p><strong>Отчество:</strong> <?= $user->patronymic ?></p>
                <div class="col-md-6">
                    <div class="avatar-container">
                        <img class="avatar" src="<?= $user->avatar ? $user->avatar : '/uploads/avatars/test.png' ?>" alt="Аватар">
                    </div>
                </div>
            <a href="<?= app()->route->getUrl('/users/set-avatar') ?>" class="btn btn-primary">Сменить аватар</a>
            <?php else: ?>
            <h2>Войдите в систему</h2>
            <hr>
            <?php endif; ?>
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
