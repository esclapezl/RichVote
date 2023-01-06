<div class="block">
<?php
use App\Model\DataObject\Question;
/** @var Question $question */
$propositions = (new \App\Model\Repository\PropositionRepository())->selectAllForQuestion($question->getId());
if(empty($propositions))
{
    echo "Il n'y a pas encore de propositions";
}
else
{
    foreach ((new \App\Model\Repository\PropositionRepository())->selectAllForQuestion($question->getId()) as $proposition){
        echo  "<a href='frontController.php?controller=Proposition&action=voter&idProposition=" . $proposition->getId() . '&score=4' . "'>" . $proposition->getIntitule();
    }
}

?>
</div>