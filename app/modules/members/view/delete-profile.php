<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h1 class="h3 mb-3">Suppression de mon compte</h1>
            <form method="post" action="" class="mb-5">
                <?php
                if (!empty($errors)) { ?>
                    <div class="mb-3 bg-light p-3 text-danger border rounded border-danger">
                        <ul>
                            <?php
                            foreach ($errors as $error) {
                                echo '<li>' . $error . '</li>';
                            } ?>
                        </ul>
                    </div>
                <?php
                }
                ?>
                <div class="mb-3">
                    <label for="pass">Mot de passe </label>
                    <input type="password" class="form-control" id="pass" name="pass" required>
                </div>

                <input type="submit" class="btn btn-danger btn-lg" type="submit" name="submit" value="Confirmer la suppression">
            </form>
        </div>
    </div>
</section>