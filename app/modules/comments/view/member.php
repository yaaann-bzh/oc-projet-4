<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="border p-3 mb-3">   
                <div class="d-flex align-items-end">                
                    <h1 class="h2 mb-0"><?= htmlspecialchars($member->pseudo(),ENT_QUOTES); ?></h1>
                    <p class="mb-1 ml-2 badge badge-success"><?php if ($member->privilege() !== null) { echo $member->privilege(); } ?></p>
                </div>         

                <p class="ml-4 mb-0"><em>Inscrit(e) depuis le <?= $member->inscriptionDate()->format('d/m/Y'); ?></em></p>                
            </div>
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
                        "><a class="page-link" href="member-<?=$member->id()?>-<?= $i; ?>"><?= $i; ?></a></li>
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
                    <p class="mb-1"><strong>Dans <a href="/post-<?= $comment->postId(); ?>#comment-<?= $comment->id(); ?>"><?= htmlspecialchars($posts[$comment->id()],ENT_QUOTES); ?></a></strong></p>
                    <p class="mb-2">
                        Le <?= $comment->addDate()->format('d/m/Y à H\hi'); 
                        if ($comment->updateDate() !== null) { echo '<em> - Modifié le ' . $comment->updateDate()->format('d/m/Y à H\hi') . '.</em>'; } ?>                  
                    </p>
                    <p class="m-0"><?= nl2br(htmlspecialchars($comment->content(),ENT_QUOTES)); ?></p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>