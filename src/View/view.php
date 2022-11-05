<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title><?php
            /** @var $pagetitle string */
            echo $pagetitle;
            ?>
        </title>

    </head>
    <body>
        <header>
            <nav class="navBar">
                <a href="#"><img src="images/logo.png" alt="RichVote" id="logo"></a>
                <ul>
                    <li><a href="#">Questions</a></li>
                    <li><a href="#">Résulats</a></li>
                </ul>
                <a href="#" id="btn-connexion">Connexion</a>
                <div class="btn">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </nav>
        </header>
        <main>
            <?php
            /** @var $cheminVueBody string */
            require __DIR__ . "/{$cheminVueBody}";
            ?>
        </main>
        <footer>
            <p>mon super site de vote</p>
        </footer>
    </body>
</html>