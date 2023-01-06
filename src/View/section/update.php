<?php
use App\Model\DataObject\Section;
/** @var Section $section */
$idQuestion = $section->getIdQuestion();
$intitule = $section -> getIntitule();
$description = $section->getDescription();
$idSection = $section->getIdSection();
?>

            <input type="text" size="50"  id=<?='i'.$idSection?> name=<?='intitule['.$idSection.']'?> value="<?=$intitule?>">
            <div class="descP"></div><h3>Description : </h3>
    <form method="post">
    <textarea type="text" rows="4" cols="100" id="mytextarea" name=<?='description['.$idSection.']'?>><?=$description?>
    </textarea></form>


