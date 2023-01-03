<?php
use \App\Lib\ConnexionUtilisateur;
use App\Model\DataObject\Groupe;
use App\Model\DataObject\User;


/** @var ?User[] $users
 * @var ?Groupe[] $groupes
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

$controller = isset($groupes)?'groupe':'user';
$role = isset($_GET['role'])?$_GET['role']:'votant';
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
    <div><h3>Rôle</h3>
        <form method='get' action='<?=$url?>'>
            <select name='role' onchange="this.form.submit();">
                <option value='votant' <?=$role=='votant'?'selected':''?>>Ajouter des votants</option>
                <option value='responsable' <?=$role=='responsable'?'selected':''?>>Ajouter des responsables</option>
            </select>
            <input type='hidden' name='controller' value='question'>
            <input type="hidden" name="action" value="<?=$controller=='user'?'addUsersToQuestion':'addGroupesRoleQuestion'?>">
            <input type="submit" name="action" value="<?=$controller!='user'?'addUsersToQuestion':'addGroupesRoleQuestion'?>">
            <input type='hidden' name='id' value='<?=$_GET['id']?>'>
        </form>
    </div>
    <h3>Ajouter</h3>
</div>
<ul>
    <?php
    if ((isset($users) && empty($users)) || (isset($groupes) && empty($groupes))){
        echo "<div class='descG'></div><div class='ligneCent'><h3> Il n'y a rien </h3></div>
                    <div class='descP'></div><div class='ligneCent'>
                    <a href=frontController.php?controller=$controller&action=readAllSelect>Clique <strong>ici</strong> pour afficher <strong>toute</strong> la liste !</a></div>";
    }
    else {
        echo "<form method='post' action='$action'>";

        if($controller=='user') {
            liste::users($users);
        }
        else {
            liste::groupes($groupes);
        }
        echo '<div class="descG"></div> <div class="ligneCent"> <input type="submit" value="Ajouter les ' . $controller .'s selectionnés" class="optQuestion"></div></form>';
    }
    ?>
</ul>
</div>
</div>

<?php
class liste{
    public static function users(array $users){
        foreach ($users as $user) {
            $idUser = rawurlencode($user->getId());
            $htmlId = ucfirst(htmlspecialchars($idUser));
            $prenom = ucfirst(htmlspecialchars($user->getPrenom()));
            $nom = ucfirst(htmlspecialchars($user->getNom()));

            echo "<div class='ligneExt'>

                            <li class='ligneExt'> <a href='frontController.php?controller=user&action=read&id=$idUser'> $htmlId</a> <span> $prenom $nom </span></span></li>
                            <label for='checkbox' class='checkbox'> 
                                <input type='checkbox' id='cb[$idUser]' name='user[$idUser]' value='$idUser'>
                            </label>
                          </div>";
        }
    }

    public static function groupes(array $groupes){
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
    }
}
?>