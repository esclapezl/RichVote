<?php
use App\Model\DataObject\User;
use \App\Lib\ConnexionUtilisateur;
use App\Model\Repository\UserRepository;
/** @var User[] $users*/
?>
<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Liste des Utilisateurs :</h1> <?php
            if(ConnexionUtilisateur::estConnecte()){
                $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                echo "<div class='responsive'>Vous êtes connecté en tant que :<h3>".ucfirst((new UserRepository())->getRole($idUser))."</h3></div>";


            }
            else{
                echo "<h3 class='responsive'>Vous n'êtes pas connecté</h3>";
            }?></div>
        <div class="ligneExt"><div class="ligne"></div><div class="ligne"></div></div>
        <div class="ligneExt"><form class="ligneAlign" method="post" action="frontController.php?controller=user&action=readAll">
                <input type="search" class="opt" name="title" id="title" placeholder="Rechercher un Utilisateur">
                <button type="submit" class="opt"><img src="../assets/img/icon-chercher.svg"></button>
                <a href="frontController.php?controller=user&action=readAll" id="refresh"><img src="../assets/img/icon-refresh.svg"></a>
            </form><h3>Rôle</h3></div>
        <ul>
            <?php
            if (empty($users)){
                echo "<div class='descG'></div><div class='ligneCent'><h3>Aucun résultat a été trouvé pour " . $_POST['title'] . " .</h3></div>
                    <div class='descP'></div><div class='ligneCent'>
                    <a href=frontController.php?controller=user&action=readAll>Clique <strong>ici</strong> pour afficher <strong>toute</strong> la liste !</a></div>";
            }
            else {
                foreach ($users as $user) {
                    echo '<div class="ligneExt"><li class="ligneExt"><a href=frontController.php?controller=user&action=read&id=' . rawurlencode($user->getId()) . '>' . ucfirst(htmlspecialchars($user->getId())) . '</a> <span>' . ucfirst(htmlspecialchars($user->getPrenom())) . ' ' . ucfirst(htmlspecialchars($user->getNom())) . '</span></span></li><h2>' . ucfirst(htmlspecialchars($user->getRole())) . '</h2></div>';
                }
            }
            ?>

        </ul>
    </div>
</div>