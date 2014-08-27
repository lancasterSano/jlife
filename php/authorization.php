<?php
require_once '.htpaths';
require_once PROJECT_PATH."/auth/y.php";
 // throw new Exception("Error Processing Request", 1);
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
require_once(PROJECT_PATH."/settings/settings.php");
/****** CONNECT TO DB_SOCIAL ******/ require_once PROJECT_PATH.'/include/dbsocial.php';
 
if($smarty) $smarty->assign('YII_APP', Yii::app());
if (Yii::app()->user->isGuest) {
    if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== "/") {
        // если перешел не авторизированый на социалку и ДО не по домену - запомнить страницу
        $url = Yii::app()->createUrl('/..'.$_SERVER['REQUEST_URI']);
        Yii::app()->user->setReturnUrl($url);
    }
    else {
        // если перешел не авторизированый на домен
        // Yii::app()->user->setReturnUrl("../.."."/pages/index.php");
        $url = Yii::app()->createUrl('/../pages/index.php');
        Yii::app()->user->setReturnUrl($url);
    }
    header("Location: ".PROJECT_PATH.SETTING_LOGIN_REDIRECT); exit;    
} else { // авторизирован в Yii
    if(!Yii::app()->request->cookies->contains("jlinyii")) { // нет куки jlinyii
        Yii::app()->user->logout();
        // echo "logout by continue";
        // exit();
        header("Location: ".PROJECT_PATH.SETTING_LOGIN_REDIRECT); exit;    
    }
    else { // есть кука для входа (jlinyii)
        $UA = new ProfileAuth($DB);
        $ProfileAuth = $UA->getProfile();
    }
}
?>