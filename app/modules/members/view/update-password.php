<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h1 class="h3 mb-3">Modifier mon mot de passe</h1>
            <form method="post" action="" class="needs-validation mb-5" novalidate="">
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
                    <label for="pass">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="pass" name="pass" required>
                    <div class="invalid-feedback">
                        Mot de passe actuel requis
                    </div>
                </div>


                <div class="mb-3">
                    <label for="newpass">Nouveau mot de passe</label>
                    <input type="password" class="form-control <?php if (isset($errors['pass_same']) OR isset($errors['pass_secu'])) { echo 'border-danger invalid-input'; } ?>" id="newpass" name="newpass" required>
                    <div class="invalid-feedback">
                        Mot de passe requis
                    </div>
                    <small class="form-text <?php if (isset($errors['pass_secu'])) { echo 'text-danger'; } else { echo 'text-muted'; } ?>">
                        <p class="m-0">Votre mot de passe doit contenir au minimum :</p>
                        <ul>
                            <li>8 caractères</li>
                            <li>1 minuscule et 1 majuscule</li>
                            <li>1 chiffre</li>
                            <li>1 caractère spécial</li>
                        </ul>
                    </small>
                </div>
            
                <div class="mb-3">
                    <label for="confirm">Confirmation du mot de passe</label>
                    <input type="password" class="form-control <?php if (isset($errors['pass_same'])) { echo 'border-danger invalid-input'; } ?>" id="confirm" name="confirm" required>
                    <div class="invalid-feedback">
                        Confirmer votre mot de passe
                    </div>
                </div>


                <input type="submit" class="btn btn-primary btn-lg" type="submit" name="submit" value="Enregistrer les modifications">
            </form>
        </div>
    </div>
</section>

<script>
    // starter JavaScript for disabling form submissions if there are invalid fields from https://getbootstrap.com/docs/4.3/examples/checkout/
    (function () {
    'use strict'

        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation')

            // Loop over them and prevent submission
            Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        }, false)
    }())

    let invalidInputs = document.getElementsByClassName('invalid-input');
    for (let i = 0; i < invalidInputs.length; i++) {
        invalidInputs[i].addEventListener('change', function () {
            invalidInputs[i].classList.remove('border-danger');
        })    
    }
</script>