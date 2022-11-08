<?php
use App\Model\DataObject\Question;
/** @var Question $question*/
?>

        <p> La question intitulée <?=$question->getIntitule()?> a bien été supprimée.</p>

<?php
require_once __DIR__ . "/../question/list.php";
?>