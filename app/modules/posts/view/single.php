<section class="container">
    <div class="row">
        <div class="col-12 col-md-8">
            <nav class="m-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="<?= $prevPost; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php
                    if ($dotBefore === true) {
                        ?>
                        <li class="page-item">
                            <a href="#" class="page-link">...</a>
                        </li>
                        <?php
                    }

                    for ($i=$begin; $i >= $end; $i--) {
                        ?>
                        <li class="page-item 
                        <?php if ((int)$post->id() === $i) { echo ' active'; } ?>
                        "><a class="page-link" href="post-<?= $i; ?>"><?= $i; ?></a></li>
                        <?php
                    }

                    if ($dotAfter === true) {
                        ?>
                        <li class="page-item">
                            <a href="#" class="page-link">...</a>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $nextPost; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="border p-3">
                <h1 class="display-5"><?= $post->title(); ?></h1>
                <hr class="featurette-divider">
                <p><?= $post->content(); ?></p>
            </div>
        </div>
    </div>
</section>

