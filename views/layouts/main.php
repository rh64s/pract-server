<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/main.css" rel="stylesheet">
    <title>Pop it MVC</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <a class="navbar-brand" href="#">Складские приколы</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= app()->route->getUrl('/hello') ?>">Главная</a>
                    </li>
                    <?php
                        if (!app()->auth::check()):
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= app()->route->getUrl('/login') ?>">Вход</a>
                            </li>
                    <?php
                        else:
                            if (app()->auth::user()->isAdmin()):
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= app()->route->getUrl('/users') ?>">Пользователи</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= app()->route->getUrl('/divisions') ?>">Подразделения</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= app()->route->getUrl('/unit-types') ?>">Типы единиц</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= app()->route->getUrl('/products') ?>">Продукты</a>
                                </li>
                    <?php elseif (app()->auth::user()->isStorekeeper()): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= app()->route->getUrl('/division-products') ?>">Продукты подразделения</a>
                                </li>
                    <?php endif ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= app()->route->getUrl('/orders') ?>">Заказы</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?= app()->route->getUrl('/logout') ?>">Выход (<?= app()->auth::user()->name ?>)</a>
                            </li>
                    <?php
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="container-md">
    <?= $content ?? '' ?>
</main>
<footer class="container-lg bg-light p-5">
    <div class="container-sm">
        <p>Университетская программа по управлению ресурсами в отделах</p>
        <p>Сделано с душой</p>
    </div>
</footer>
</body>
</html>