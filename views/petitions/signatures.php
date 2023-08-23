<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Petition */

?>
<div class="envolve signatures">

    <ul class="envolve signatures-list">
    <?php foreach($signatures as $signature): ?>
        <li class="envolve signature">
            <?= Html::encode($signature->getFullName($options['lastnames'])) ?>
            <?php if ($signature->message && $options['messages']): ?>
                <div class="envolve message">
                    <?= Html::encode($signature->message) ?>
                </div>
            <?php endif ?>
        </li>
    <?php endforeach ?>
    </ul>

</div>
