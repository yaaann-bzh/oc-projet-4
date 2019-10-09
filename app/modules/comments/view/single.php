<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8">
            <h1 class="h2">Edition de commentaire</h1>
            <?php if ((int)$comment->removed() === 1) { ?>
                <div class="p-3 mb-3 ml-3 bg-light border border-dark rounded">
                    <p class="mt-3">Commentaire supprimé</p>
                    <p><a href="/post-<?= $comment->postId(); ?>#comment-<?= $comment->id(); ?>"><i class="fas fa-long-arrow-alt-left"></i> Retour à la publication</a></p>
                    <p><a href="/member-<?= $comment->memberId(); ?>-1">Autres commentaires de <?= htmlspecialchars($member->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></a></p>
                </div>
            <?php } else { ?>     
                <form method="POST" action="" class="mb-3">
                    <div class="form-group">
                        <p class="mb-1">
                            <strong><a href="/member-<?= $comment->memberId(); ?>-1"><?= htmlspecialchars($member->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></a></strong>
                            <span class="mb-1 ml-2 badge badge-success"><?php if ($member->privilege() !== null) { echo $member->privilege(); } ?></span>
                                - le <?= $comment->addDate()->format('d/m/Y à H\hi'); 
                            if ($comment->updateDate() !== null) { echo '<em> - Modifié le ' . $comment->updateDate()->format('d/m/Y à H\hi') . '.</em>'; } ?>                  
                        </p>
                        <p class="mb-2"><em>dans <a href="/post-<?= $comment->postId(); ?>#comment-<?= $comment->id(); ?>"><?= htmlspecialchars($post->title(), ENT_QUOTES | ENT_SUBSTITUTE); ?></a></em></p>
                        </div>
                    <?php if ($updated === 'updated') { ?>        
                        <div class="form-group row">
                            <div class="col-12 col-lg-6 bg-light" id="valid-message">
                                <p>Le commentaire a bien été modifié.</p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="content">Commentaire :</label>
                        <textarea class="form-control" name="content" id="content" cols="30" rows="5" required><?= htmlspecialchars($comment->content(),ENT_QUOTES | ENT_SUBSTITUTE); ?></textarea>
                    </div>
                    <input type="submit" class="btn btn-success mr-3" name="action" value="Modifier">
                    <input type="submit" class="btn btn-danger" name="action" value="Supprimer">
                </form>
            <?php }?>
        </div>
    </div>
</section>