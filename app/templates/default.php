<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="http://projet4.yaaann.ovh/public/assets/quill-ink.png" sizes="32x32">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" 
            integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" 
            crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"    
            crossorigin="anonymous">

    <title><?= $tabTitle; ?></title>
</head>
<body>
    <header class="site-header sticky-top">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">J. Forteroche</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <!--Prévoir le changement de classe des liens pour activation (si besoin)-->
                        <li class="nav-item active">
                            <a class="nav-link" href="/"><i class="fas fa-home"></i> Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="far fa-comment-dots"></i> Derniers commentaires</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav" id="user">
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class="fas fa-user-alt"></i> Connection</a>
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
    </header>
   
    <?php //var_dump($tabTitle);?>

    <div class="container" id="top-page">
        <?= $content; ?>
        <hr class="featurette-divider">
    </div>

    <footer class="container">
        <p class="float-right"><a href="#top-page">Haut de page</a></p>
        <p> 2019 - Yaaann · <a href="https://www.yaaann.ovh">Mes autres projet</a> · <a href="https://openclassrooms.com/fr/">OpenClassrooms</a></p>
    </footer>
    
</body>
</html>