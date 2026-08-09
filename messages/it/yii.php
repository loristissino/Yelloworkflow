<?php
// Include Yii's default Italian translations
$default = require Yii::getAlias('@yii/messages/it/yii.php');

// Add or override specific messages from the plugin
return array_merge($default, [
    'No items.' => 'Nessun elemento.',
    'Action not found.' => 'Azione non trovata',

    // Add other plugin messages here as needed
]);

