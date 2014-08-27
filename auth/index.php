<?php
define("YII_ENBLE_ERROR_HANDLER",false);
define("YII_ENBLE_EXCEPTION_HANDLER",false);
error_reporting(E_ALL ^ E_NOTICE);

$webRoot=dirname(__FILE__);
// if(true){
//     // define('YII_DEBUG', true);
//     defined('YII_DEBUG') or define('YII_DEBUG',true);
//     require_once(dirname($webRoot).'/../frameworks/yii-1.1.14.f0fee9/framework/yii.php');
//     $configFile=$webRoot.'/protected/config/production.php';
// }
if($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='devsjlife.com' || $_SERVER['HTTP_HOST']=='jlifelocal.ru'){
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    require_once(dirname($webRoot).'/../frameworks/yii-1.1.14.f0fee9/framework/yii.php');
    $configFile=$webRoot.'/protected/config/dev.php';
    define("JLIFE_DB_SOCIAL_USERNAME", "devsjlife_www");
    define("JLIFE_DB_SOCIAL_NAME", "devsjlife_devssocial");
    define("JLIFE_DB_DO_USERNAME", "devsjlife_www");
    define("JLIFE_DB_DO_NAME", "devsjlife_devsdo");
    
    define("JLIFE_ICON", "jlife_icon_local"); // jlife_icon_publish jlife_icon_server jlife_icon_local
}
else { 
    define('YII_DEBUG', false);
    require_once(dirname($webRoot).'/../frameworks/yii-1.1.14.f0fee9/framework/yiilite.php');
    $configFile=$webRoot.'/protected/config/production.php';
    define("JLIFE_DB_SOCIAL_USERNAME", "admin_www");
    define("JLIFE_DB_SOCIAL_NAME", "admin_primesocial");
    define("JLIFE_DB_DO_USERNAME", "admin_www");
    define("JLIFE_DB_DO_NAME", "admin_primedo");

    define("JLIFE_ICON", "projecticon"); // projecticon
} 
Yii::createWebApplication($configFile)->run();