<?php
//liste des propositions pour une question donnée

use App\Model\DataObject\Proposition;
/** @var array $propositions */
?>

<div class="block">
    <div class="text-box">
        <?php foreach ($propositions as $proposition) {
            echo '<p><a href= frontController.php?controller=' . $_GET['controller'] . '&action=viewProposition&id=' . $proposition->getIdProposition() . '>' . $proposition->getIdProposition() . '</a></p>';
        }?>
    </div>
</div>
