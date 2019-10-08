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
                <p> 
                    <em>Publié le <?= $post->addDate()->format('d/m/Y à H\hi'); ?></em>
                    <?php
                    if ($post->updateDate() !== null) { 
                        echo '<em class="text-danger"> - Modifié le ' . $post->updateDate()->format('d/m/Y à H\hi') . '.</em>'; 
                    } ?>                  
                </p>
                <hr class="my-4">
                <p class="text-justify"><?= nl2br(htmlspecialchars($post->content(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>
            </div>
            <div class="p-3">
                <?php
                foreach ($comments as $comment ) {
                ?>
                <div class="p-3 mb-3 bg-light border border-dark rounded" id="comment-<?= $comment->id(); ?>">
                    <p>
                        <strong><a href="/member-<?= $comment->memberId(); ?>-1"><?= htmlspecialchars($members[$comment->id()]->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></a></strong>
                        <span class="mb-1 ml-2 badge badge-success"><?php if ($members[$comment->id()]->privilege() !== null) { echo $members[$comment->id()]->privilege(); } ?></span>
                         - le <?= $comment->addDate()->format('d/m/Y à H\hi'); 
                        if ($comment->updateDate() !== null) { echo '<em> - Modifié le ' . $comment->updateDate()->format('d/m/Y à H\hi') . '.</em>'; } ?>                  
                    </p>
                    <p class="m-0"><?= nl2br(htmlspecialchars($comment->content(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>
                </div>
                <?php
                }
                ?>         
            </div>
        </div>
    </div>
</section>

