<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'frontend',       // 鍞竴閿墠缂�
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,//闅愯棌index.php
            'enableStrictParsing' => false,
            //'suffix' => '.html',//鍚庣紑锛屽鏋滆缃簡姝ら」锛岄偅涔堟祻瑙堝櫒鍦板潃鏍忓氨蹇呴』甯︿笂.html鍚庣紑锛屽惁鍒欎細鎶�404閿欒
            'rules' => [
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                //'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>?id=<id>'
                //'detail/<id:\d+>' => 'site/detail?id=$id',
                //'post/22'=>'site/detail',
                //'<controller:detail>/<id:\d+>' => '<controller>/index',
                '' => 'article/index',
                '<controller:w+>/<action:\w+>'=>'<controller>/<action>',
                '<page:\d+>' => 'article/index',
                'login' => 'site/login',
                'sinup' => 'site/signup',
                'about|contact' => 'page/view',
                'page/<name:\w+>' => 'page/view',
                'view/<id:\d+>' => 'article/view',
                'comment' => 'article/comment',
                'article/view/id/<id:\d+>' => 'article/view',
                'Index/detail/id/<id:\d+>' => 'site/detail',
                'search' => 'search/index',
                'cat/<cat:\w+>' => 'article/index',
                'list/<page:\d+>' => 'site/index',
                'python|java|javascript' => 'article/index',
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',

                    ],
                ],
                'front*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'frontend' => 'frontend.php',
                        'app/error' => 'error.php',

                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'on beforeRequest' => function($event){
        \feehi\components\Feehi::frontendInit();
        if(isset(\yii::$app->session['view'])) \yii::$app->viewPath = dirname(__DIR__).'/'.\yii::$app->session['view'];
        if(isset(\yii::$app->session['language'])) \yii::$app->language = yii::$app->session['language'];
    }
];
