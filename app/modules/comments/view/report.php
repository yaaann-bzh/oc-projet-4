<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8">
            <h1 class="h2">Signaler un commentaire</h1>
            <div class="border p-3 mb-3 bg-light">
                <p class="mb-1">
                    <strong><a href="/member-<?= $comment->memberId(); ?>-1"><?= htmlspecialchars($member->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></a></strong>
                    <span class="mb-1 ml-2 badge badge-success"><?php if ($member->privilege() !== null) { echo $member->privilege(); } ?></span>
                        - le <?= $comment->addDate()->format('d/m/Y à H\hi'); 
                    if ($comment->updateDate() !== null) { echo '<em> - Modifié le ' . $comment->updateDate()->format('d/m/Y à H\hi') . '.</em>'; } ?>                  
                </p>
                <p class="mb-2"><em>dans <a href="/post-<?= $comment->postId(); ?>#comment-<?= $comment->id(); ?>"><?= htmlspecialchars($post->title(), ENT_QUOTES | ENT_SUBSTITUTE); ?></a></em></p>
                <p class="m-0"><?= nl2br(htmlspecialchars($comment->content(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>
            </div>
            <?php
            if (isset($report)) { ?>
                <div class="p-3 m-3 bg-light border border-dark rounded">
                    <p class="text-danger">Nous avons bien reçu votre signalement concernant ce commentaire.<br/>
                    Il sera traité par un modérateur dans les meilleurs delais.<br/>
                    <em>Transmis le <?= $report->reportDate()->format('d/m/Y à H\hi'); ?></em></p>
                </div>

            <?php } else { ?>      
            <form method="POST" action="" class="mb-3">
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="motif" id="injure" value="injure" required>
                        <label class="form-check-label" for="injure">Contenu injurieux</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="motif" id="spam" value="spam" required>
                        <label class="form-check-label" for="spam">Spam</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="motif" id="autre" value="autre" required>
                        <label class="form-check-label" for="autre">Autre</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="content">Souhaitez vous ajouter un commentaire :</label>
                    <textarea class="form-control" name="content" id="content" cols="30" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Signaler</button>
            </form>
            <?php } ?>
        </div>
    </div>
</section>
