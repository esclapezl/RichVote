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
            <nav class="navBar">
                <a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=accueil"><img src="img/logo.png" alt="RichVote" id="logo"></a>
                <ul>
                    <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=readAll">Questions</a></li>
                    <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=readAll">Résulats</a></li>
                    <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=about">Contributeurs</a></li>
                </ul>
                <a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=connexion" id="btn-connexion"><?php if($_GET['controller']=='user'){echo "Connexion";} else{echo ucfirst(strtolower($_GET['controller']));}?></a>
                <div class="btn">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </nav>
        <main>
            <?php
            /** @var $cheminVueBody string */
            require __DIR__ . "/{$cheminVueBody}";
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
                <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=accueil">Accueil</a></li>
                <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=readAll">Questions</a></li>
                <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=readAll">Résultats</a></li>
                <li><a href="frontController.php?controller=<?php echo $_GET['controller']?>&action=about">Contributeurs</a></li>
            </ul>
            <?php
            if(($_GET['controller']) == 'user'){
                echo '<p><a href="frontController.php?controller=user&action=connexion" id="txtEffet">Connexion</a> |   Pas encore inscrit ? <a href="frontController.php?controller=user&action=inscription" id="txtEffet">Inscrivez vous</a></p>';

             }
            else{
                echo '<p><a href="frontController.php?controller=user&action='.$_GET['action'].'" id="txtEffet">Deconnexion</a>' ;

            }

            ?>
            <p>Copyright &copy; RichVote | Tous droits reserves</p>
        </footer>
    </body>
</html>