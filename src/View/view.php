<?php
use App\Lib\ConnexionUtilisateur;
use App\Model\Repository\UserRepository;


if((new ConnexionUtilisateur())->estAdministrateur())
    ?>


<!DOCTYPE html>
<html lang="fr" >
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
                    <li><a href="frontController.php?controller=question&action=readAllArchives">Archives</a></li>
                    <li><a href="frontController.php?controller=user&action=readAll">Contributeurs</a></li>
                </ul>

                    <?php
                    if((new ConnexionUtilisateur())->estAdministrateur() )
                    {
                        echo '<div id="btn-role"> Administrateur</div>';
                    }
                    ?>



                    <?php
                    if((new ConnexionUtilisateur())->estConnecte()) {
                        echo  '<div class="ligneAlign"><a id="btn-connexion" href="frontController.php?controller=user&action=read&id='.(new ConnexionUtilisateur())->getLoginUtilisateurConnecte() .'">'. (new ConnexionUtilisateur())->getLoginUtilisateurConnecte().' </a>
                                <a id="btn-connexion" href="frontController.php?controller=user&action=deconnexion">Deconnexion </a></div>';

                    }
                    else {echo  '<a id="btn-connexion" href="frontController.php?controller=user&action=connexion">Connexion </a>';}
                    ?>

                    <div class="btn">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    </div>

                </div>
            </nav>
        <main>

            <?php


            foreach(['danger', 'warning', 'info', 'success'] as $categorie){
                if(MessageFlash::contientMessage($categorie)){
                    foreach(MessageFlash::lireMessages($categorie) as $message){
                        echo '<div class="ligneCent"><div class="alert alert-' .$categorie.'">' . $message . '</div></div>';
                    }
                }
            }

            /** @var $cheminVueBody string */
            require __DIR__ . "/{$cheminVueBody}";
            ?>
            <script>
                const menuHamburger = document.querySelector(".btn")
                const navLinks = document.querySelector(".navBar ul")

                menuHamburger.addEventListener('click',()=>{
                    navLinks.classList.toggle('mobile-menu')
                })
            </script>
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

            <p>Copyright &copy; RichVote | Tous droits réservés</p>
        </footer>
    </body>
</html>