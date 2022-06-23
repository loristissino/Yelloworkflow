<?php

use yii\helpers\Url;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = "Sbattezzati";
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Sbattezzati</h1>
        
        <p class="lead">Bonifica statistica delle appartenenze religiose</p>
        <p><?php //=  Yii::$app->getSecurity()->generatePasswordHash('12345')  ?></p>

        <p>
            <?= Html::a('Campagna', ['site/dashboard'], ['class'=>"btn btn-lg btn-success"]) ?>
            <?= Html::a('Mappa', ['site/dashboard'], ['class'=>"btn btn-lg btn-success"]) ?>
            <?= Html::a('Informazioni e modulo', ['site/dashboard'], ['class'=>"btn btn-lg btn-success"]) ?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Sbattèzzati!</h2>
                <p><em>(sbat-tèz-za-ti) Verbo in forma imperativa</em></p>
                <p>Esortazione ad un'azione da parte tua: se non credi, s.!</p>
                <p>Non ti riconosci (più) nella definizione di fedele di una chiesa? Puoi <em>sbattezzarti</em>, ossia chiedere che venga posta un'annotazione nei registri ecclesiastici della tua non appartenenza alla chiesa stessa.</p>
                
            </div>
            <div class="col-lg-4">
                <h2>Sbattezzàti</h2>
                <p><em>(sbat-tez-zà-ti) Participio passato</em></p>
                <p>Le persone che hanno deciso di partecipare alla nostra campagna di <em>sbattezzo</em>: cinque ragazzi si sono s. ieri a Roma.</p>
                <p>Molti si sono sbattezzati e hanno deciso di farlo sapere (alcuni in maniera anonima, altri "mettendoci la faccia") a tutti.
                Puoi vedere una mappa oppure decidere di esserne parte inviando alcuni dati sul tuo sbattezzo.</p>
            </div>
            
            <div class="col-lg-4">
                <h2>Sbattezzàti</h2>
                <p><em>(sbat-tez-zà-ti) Sostantivo</em></p>
                <p>Le persone che si sono sbattezzate: sono almeno Xmila gli s. in Italia.</p>
                <p>Molti sbattezzati sono impegnati attivamente per rivendicare il diritto di tutti di essere liberi dalla religione. Puoi darci una mano anche tu.</p>

            </div>
        </div>
    </div>
</div>
