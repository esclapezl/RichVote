<?php
use App\Model\DataObject\Question;
/** @var Question $question*/
?>
<div class="block">
    <div class="text-box">
        <p> La question intitulée <?=$question->getIntitule()?> a bien été supprimée.</p>;
    </div>
</div>
<?php
require_once __DIR__ . "/../vote/listQuestion.php";
?>