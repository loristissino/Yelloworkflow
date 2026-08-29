<?php

/* @var $this yii\web\View */

$redirect = $return ? \yii\helpers\Url::toRoute($return) : Yii::$app->homeUrl;

$this->registerJs("
    localStorage.removeItem('ywf_user');
    
    if (window.indexedDB) {
        const dbName = 'ywf_db';
        var request = indexedDB.deleteDatabase(dbName);

        request.onsuccess   = function () {
            console.log(`IndexedDB «{dbName}» deleted successfully.`);
        };
        request.onerror     = function (e) {
            console.error(`Error deleting IndexedDB «{dbName}»:`, e.target.error);
        };
        request.onblocked   = function () {
            console.warn(`Deletion of IndexedDB «{dbName}» blocked – another tab may be using it.`);
        };
    } else {
        console.warn('IndexedDB not supported in this browser.');
    }

    window.location = '" . $redirect . "';
");
?>
