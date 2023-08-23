<?php

namespace app\assets;

use yii\web\AssetBundle;

class PagedAsset extends AssetBundle

{

    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [

    ];

    public $js = [

        'js/paged.polyfill.js?v20230220',

    ];

    public $depends = [

    ];

}
