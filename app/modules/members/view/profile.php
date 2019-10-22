<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h1 class="h3 mb-3">Mon profil</h1>
            <?php if (isset($updated)) { ?>        
                <div class="form-group row">
                    <div class="col-12 col-lg-6 bg-light" id="valid-message">
                        <p>Vorre profil a été mise à jour.</p>
                    </div>
                </div>
            <?php } ?>
            <h3><?= htmlspecialchars($member->pseudo(),ENT_QUOTES | ENT_SUBSTITUTE); ?></h3>
            <p>Inscrit(e) depuis le <?= $member->inscriptionDate()->format('d/m/Y'); ?></p>
            <ul>
                <?php if ($member->privilege() !== null) { ?>
                    <li><?= $activity['posts']; ?> Publications</li>
                <?php } ?>
                <li>
                    <?= $activity['comments']; ?> Commentaires
                    <small class="ml-2"><a href="/member-<?= $member->id(); ?>-1">Voir tous</a></small>
                </li>
            </ul>
            <form class="mb-3" method="POST" action="password-update-<?= $member->id(); ?>">
                <input type="submit" class="btn btn-primary mr-3" name="action" value="Modifier mon mot de passe">
            </form>
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th scope="row">Nom : </th>
                        <td><?= htmlspecialchars($member->lastname(),ENT_QUOTES | ENT_SUBSTITUTE); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Prénom : </th>
                        <td><?= htmlspecialchars($member->firstname(),ENT_QUOTES | ENT_SUBSTITUTE); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email : </th>
                        <td><?= htmlspecialchars($member->email(),ENT_QUOTES | ENT_SUBSTITUTE); ?></td>
                    </tr>
                </tbody>
            </table>
            <form method="POST" action="" class="mb-3">
                <input type="submit" class="btn btn-success mr-3" name="action" value="Modifier">
                <input type="submit" class="btn btn-danger" name="action" value="Supprimer">
            </form>
        </div>
    </div>
</section>