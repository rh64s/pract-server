<h1>Список статей</h1>
<hr>
<ul class="list-group">
    <?php
    foreach ($posts as $post) {
        echo '<li class="list-group-item">' . $post->title . '</li>';
    }
    ?>
</ul>