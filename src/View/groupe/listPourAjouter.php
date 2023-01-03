<?php
use \App\Lib\ConnexionUtilisateur;
use App\Model\DataObject\Groupe;

/** @var Groupe[] $groupes
 * @var string $action
 * @var string $privilegeUser
 */
// trouver un moyen de récupérer l'url pour faire un refresh
$url = 'frontController.php?';
$i = sizeof($_GET);

foreach ($_GET as $key=>$value) {
    if(array_search($value, $_GET)>0){
        $url.='&';
    }
    $url.="$key=$value";
}
?>

<div class="block">
    <div class="text-box">
        <div class="ligneExt"> <h1>Liste des Utilisateurs :</h1> <?php
            if(ConnexionUtilisateur::estConnecte()){
                echo "<div class='responsive'>Vous êtes connecté en tant que :<h3>" . ucfirst($privilegeUser) . "</h3></div>";
            }
            else{
                echo "<h3 class='responsive'>Vous n'êtes pas connecté</h3>";
            }?></div>
        <div class="ligneExt">
            <div class="ligne"></div>
            <div class="ligne"></div>
        </div>
        <div class="ligneExt"><form class="ligneAlign" method="post" action="<?=$url?>">
                <input type="search" class="opt" name="filtre" id="filtre" placeholder="Rechercher un Utilisateur">
                <button type="submit" class="opt"><img src="../assets/img/icon-chercher.svg"></button>
                <a href="<?=$url?>" id="refresh">
                    <img src="../assets/img/icon-refresh.svg">
                </a>
            </form>
            <h3>Rôle</h3>
            <h3>Ajouter</h3>
        </div>
        <ul>
            <?php
            if (empty($groupes)){
                echo "<div class='descG'></div><div class='ligneCent'><h3> Il n'y a rien </h3></div>
                    <div class='descP'></div><div class='ligneCent'>
                    <a href=frontController.php?controller=groupe&action=readAllSelect>Clique <strong>ici</strong> pour afficher <strong>toute</strong> la liste !</a></div>";
            }
            else {
                echo "<form method='post' action='$action'>";
                foreach ($groupes as $groupe) {
                    $nomGroupe = rawurlencode($groupe->getId());
                    $htmlnom = ucfirst(htmlspecialchars($nomGroupe));

                    echo "<div class='ligneExt'>

                            <li class='ligneExt'> <a href='frontController.php?controller=groupe&action=read&nomGroupe=$nomGroupe'> $htmlnom</a></span></li>
                            <label for='checkbox' class='checkbox'> 
                                <input type='checkbox' id='cb[$nomGroupe]' name='groupe[$nomGroupe]' value='$nomGroupe'>
                            </label>
                          </div>";
                }
                echo '<div class="descG"></div> <div class="ligneCent"> <input type="submit" value="Ajouter les groupes selectionnés" class="optQuestion"></div></form>';
            }
            ?>
        </ul>
    </div>
</div>