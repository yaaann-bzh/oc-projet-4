<?php
include(__DIR__.'../../../comments/view/single.php');
?>
<section class="container">
    <hr>
    <div class="row d-flex">
        <div class="col-12 d-flex">
            <h2 class="h4 mr-4">Les signalements :</h2>
            <form method="POST" action="">
                <input type="submit" class="btn btn-primary" name="action" value="Vider la liste">
            </form>
        </div>
        <?php 
        if ($comment->updateDate() > $comment->reportDate()) { ?>
            <p class="bg-warning text-center p-3 m-3 col-12 col-lg-8 rounded-pill"><em>NB : Ce commentaire a été modifié depuis son signalement</em></p>
        <?php }  
        foreach ($reports as $report) {
        ?>
            <div class="m-3 p-3 col-12 col-lg-5 border bg-light">
                <div class="d-flex">
                    <div class="flex-fill">
                        <p class="m-0">Par <strong><?= $members[$report->id()]; ?></strong> le <?= $report->reportDate()->format('d/m/Y à H\hi'); ?></p>
                        <p class="m-0"><?= nl2br(htmlspecialchars($report->content(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>
                    </div>
                    <?php 
                    if ($comment->updateDate() > $comment->reportDate()) { ?>
                        <button class="collapsed btn ml-3 btn-light btn-outline-dark" type="button" data-toggle="collapse" data-target="#report-<?= $report->id(); ?>" aria-controls="report-<?= $report->id(); ?>" aria-expanded="false" aria-label="Toggle">
                            Contenu d'origine
                        </button>
                    <?php } ?>
                </div>
                <p class="collapse m-2 pr-3 pl-3 border rounded text-muted" id="report-<?= $report->id(); ?>" style=""><?= nl2br(htmlspecialchars($report->commentContent(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>
            </div>
        <?php
        }
        ?>
    </div>
</section>
