<?php

raoul2000\workflow\view\WorkflowViewWidget::widget([
	'workflow'    => $model,
	'containerId' => 'myWorkflowView',
    'seed'        => $seed,
]);
?>

<div id="myWorkflowView" style="height: 600px;"></div>
