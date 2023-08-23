<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $petitions app\models\Petition */

?>

<?php if (sizeof($petitions)>0): ?>

<ul class="envolve active-petitions-index">
<?php foreach($petitions as $petition): ?>

    <li class="envolve petition-item">
        <span class="envolve petition-title"><?= $petition->title ?></span>, 
        <span class="envolve petition-slug"><?= $petition->slug ?></span>
    </li>

<?php endforeach ?>
</ul>


<?php endif ?>

