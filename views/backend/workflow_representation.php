<?php

raoul2000\workflow\view\WorkflowViewWidget::widget([
	'workflow'    => $model,
	'containerId' => 'myWorkflowView',
    'seed'        => $seed,
    'gender'      => $gender,
]);

// Note to myself: to get the translated names of the labels, operate on WorkflowViewWidget.php

?>

<div id="myWorkflowView" style="height: 600px;"></div>
