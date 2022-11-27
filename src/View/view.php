<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="icon" href="../assets/img/favicon.ico" />
        <title><?php
            /** @var $pagetitle string */

            use App\Lib\MessageFlash;

            echo $pagetitle;
            ?>
        </title>

    </head>
    <body>
            <nav>
                <div class="navBar">
                <a href="frontController.php?controller=user&action=accueil"><img src="../assets/img/logo.png" alt="RichVote" id="logo"></a>
                <ul>
                    <li><a href="frontController.php?controller=question&action=readAll">Questions</a></li>
                    <li><a href="frontController.php?controller=question&action=readAll">Résultats</a></li>
                    <li><a href="frontController.php?controller=user&action=readAll">Contributeurs</a></li>
                </ul>
                    <a id="btn-connexion" href="frontController.php?controller=user&action=connexion">Connexion </a>
                    <div class="btn">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    </div>
                </div>
            </nav>
        <main>
            <?php
            require __DIR__ . "/{$cheminVueBody}";

            foreach(['danger', 'warning', 'info', 'success'] as $categorie){
                if(MessageFlash::contientMessage($categorie)){
                    foreach(MessageFlash::lireMessages($categorie) as $message){
                        echo '<div class="alert alert-' .$categorie.'">' . $message . '</div>';
                    }
                }
            }
            ?>
        </main>
        <footer>
            <div class="vagues">
                <div class="vague" id="vague1"></div>
                <div class="vague" id="vague2"></div>
                <div class="vague" id="vague3"></div>
                <div class="vague" id="vague4"></div>

            </div>
            <ul>
                <li><a href="frontController.php?controller=user&action=accueil">Accueil</a></li>
                <li><a href="frontController.php?controller=question&action=readAll">Questions</a></li>
                <li><a href="frontController.php?controller=question&action=readAll">Résultats</a></li>
                <li><a href="frontController.php?controller=user&action=about">Contributeurs</a></li>
            </ul>
            <p><a href="frontController.php?controller=user&action=connexion" id="txtEffet">Connexion</a> |   Pas encore inscrit ? <a href="frontController.php?controller=user&action=inscription" id="txtEffet">Inscrivez vous</a></p>;

            <p>Copyright &copy; RichVote | Tous droits réservés</p>
        </footer>
    </body>
</html>