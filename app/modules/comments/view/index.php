<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8">
            <h1 class="h2">Derniers commentaires</h1>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                    <a class="page-link" href="<?= $prevIndex; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                    </li>
                    <?php
                    for ($i=1; $i <= $nbPages; $i++) { 
                        ?>
                        <li class="page-item 
                        <?php if ($index === $i) { echo ' active'; } ?>
                        "><a class="page-link" href="comments-index-<?= $i; ?>"><?= $i; ?></a></li>
                        <?php
                    }
                    ?>
                    <li class="page-item">
                    <a class="page-link" href="<?= $nextIndex; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                    </li>
                </ul>
            </nav>

            <?php
            foreach ($comments as $comment) {
                ?>
                <div class="border p-3 mb-3 bg-light">
                    <p class="mb-1">
                        <strong><a href="/member-<?= $comment->memberId(); ?>-1"><?= htmlspecialchars($members[$comment->id()]->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></a></strong>
                        <span class="mb-1 ml-2 badge badge-success"><?php if ($members[$comment->id()]->privilege() !== null) { echo $members[$comment->id()]->privilege(); } ?></span>
                         - le <?= $comment->addDate()->format('d/m/Y à H\hi'); 
                        if ($comment->updateDate() !== null) { echo '<em> - Modifié le ' . $comment->updateDate()->format('d/m/Y à H\hi') . '.</em>'; } ?>                  
                    </p>
                    <p class="mb-2"><em>dans <a href="/post-<?= $comment->postId(); ?>#comment-<?= $comment->id(); ?>"><?= htmlspecialchars($posts[$comment->id()],ENT_QUOTES | ENT_SUBSTITUTE); ?></a></em></p>
                    <p class="m-0"><?= nl2br(htmlspecialchars($comment->content(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>

                    <?php include(__DIR__ . '/../../../templates/_comment_admin.php'); ?>

                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>