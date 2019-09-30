<h1>Titre du Roman</h1>

<section class="row">
    <div class="col-8">
        <?php

        foreach ($postsList as $post)
        {
            ?>
            <h2><a href="post-<?= $post->id(); ?>.html"><?= $post->title(); ?></a></h2>
            <p><?= $post->getExerpt(); ?> ... | <a href="post-<?= $post->id(); ?>.html">Lire la suite</a></p>
            <?php
        }
        ?>
    </div>

</section>
