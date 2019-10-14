<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8">
            <h1 class="h2">Derniers signalements</h1>
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
                        "><a class="page-link" href="index-<?= $i; ?>"><?= $i; ?></a></li>
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
            foreach ($reportedComments as $reportedComment) {
                ?>
                <div class="border p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between">
                        <p><strong><a href="/admin/reports-<?= $reportedComment->id(); ?>" >Commentaire de <?= htmlspecialchars($members[$reportedComment->id()]->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></a></strong> du <?= $reportedComment->addDate()->format('d/m/Y à H\hi'); ?></p>
                        <div class="flex-shrink-0">
                            <a href="#" title="Signalement" class="badge badge-danger badge-pill"><?= $nbReports[$reportedComment->id()]; ?></a>
                            <button class="collapsed btn btn-outline-danger ml-3" type="button" data-toggle="collapse" data-target="#report-<?= $reportedComment->id(); ?>" aria-controls="report-<?= $reportedComment->id(); ?>" aria-expanded="false" aria-label="Toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                    </div>
                    <div class="collapse" id="report-<?= $reportedComment->id(); ?>" style="">
                        <?php 
                        if ($reportedComment->updateDate() > $reportedComment->reportDate()) { ?>
                            <p class="text-danger ml-3"><em>NB : Ce commentaire a été modifié depuis son signalement</em></p>
                        <?php } ?>
                        <p class="m-0"><?= nl2br(htmlspecialchars($reportedComment->content(),ENT_QUOTES | ENT_SUBSTITUTE)); ?></p>
                        <p class="mt-2 mb-0 text-center"><a href="/admin/reports-<?= $reportedComment->id(); ?>">Liste des signalements <i class="fas fa-arrow-circle-right"></i></a></p>   
                    </div>
                </div>              
                <?php
            }
            ?>
        </div>
    </div>
</section>