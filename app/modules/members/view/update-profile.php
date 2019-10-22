<section class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <h1 class="h3 mb-3">Modifier mes informations</h1>
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
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstname">Prénom</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required value="<?= $member->firstname(); ?>">
                        <div class="invalid-feedback">
                            Un prénom valide est requis
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastname">Nom</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required value="<?= $member->lastname(); ?>">
                        <div class="invalid-feedback">
                            Un nom valide est requis
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" 
                        class="form-control <?php if (isset($errors['pseudo'])) { echo 'border-danger invalid-input'; } ?>" 
                        id="pseudo" name="pseudo" required 
                        value="<?= $member->pseudo(); ?>">
                    <div class="invalid-feedback" style="width: 100%;">
                        Pseudo requis
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">E-mail</label>
                    <input type="email" 
                        class="form-control <?php if (isset($errors['email'])) { echo 'border-danger invalid-input'; } ?>" 
                        id="email" name="email" placeholder="you@example.com" required 
                        value="<?= $member->email(); ?>">
                    <div class="invalid-feedback">
                        Merci de renseigner une adresse mail valide
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <label for="pass">Mot de passe</label>
                    <input type="password" class="form-control <?php if (isset($errors['pass_same']) OR isset($errors['pass_secu'])) { echo 'border-danger invalid-input'; } ?>" id="pass" name="pass" required>
                    <div class="invalid-feedback">
                        Mot de passe requis
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