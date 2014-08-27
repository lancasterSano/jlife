<?php
$yii = dirname(__FILE__) . '/../../../frameworks/yii-1.1.14.f0fee9/framework/yii.php';
$config = dirname(__FILE__) . '/config/console.php';
 
defined('YII_DEBUG') or define('YII_DEBUG', true);
 
require_once($yii);
Yii::createConsoleApplication($config)->run();