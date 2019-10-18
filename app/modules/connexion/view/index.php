<div class="container-fluid d-flex vh-80 align-items-center justify-content-center bg-light">
    <form method="post" action="" class="form-signin">

        <div class="text-center mb-3">
            <h1 class="h3 mb-3 font-weight-normal">Bon retour parmis nous</h1>
        </div>
                
        <div class="text-center mb-4" id="connectincorrect">
            <?php if (isset($invalid)) { ?>
            <p class="mb-4">Identifiant ou mot de passe incorrect !</p>
            <?php } ?>
        </div> 
        
        <div class="form-label-group">
            <input type="text" name="login" id="inputLogin" class="form-control" placeholder="Adresse Email ou Pseudo" required="" autofocus="">
            <label for="inputLogin">Adresse Email ou Pseudo</label>
        </div>

        <div class="form-label-group">
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required="">
            <label for="inputPassword">Mot de passe</label>
        </div>

        <div class="checkbox mb-3">
            <label>
            <input type="checkbox" name="remember" value="remember-me"> Se souvenir de moi
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
        <div class="form-label-group">
            <p class="m-4 text-center">Nouveau sur le site ? <a href="/inscription">S'inscrire</a></p>
        </div>
    </form>
</div>
<form action=""></form>
