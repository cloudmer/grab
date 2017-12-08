<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
\Yii::setAlias('@webroot', __DIR__);


$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$mailer = require(__DIR__ . '/mailer.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => $mailer['sendEmailUser'],
                'password' => $mailer['sendEmailPassword'],
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>[$mailer['messageConfigFrom']=>'机房提醒']
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];
