<?php
use App\Model\DataObject\Section;
/** @var Section $section */
$idQuestion = $section->getIdQuestion();
$intitule = $section -> getIntitule();
$description = $section->getDescription();
$idSection = $section->getIdSection();
?>

        <p>
            <input type="text" id=<?='i'.$idSection?> name=<?='intitule['.$idSection.']'?> value="<?=$intitule?>">
            <input type="text" id=<?='d'.$idSection?> name=<?='description['.$idSection.']'?> value="<?=$description?>">
        </p>
