<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

##############################################
if (isset ($_POST['idLesson']))   { $idLesson=$_POST['idLesson'];}
    
    # Получаем дату конкретного урока
    $dL = $DB_DO->getAll(QSDO::$getDateLesson, $idLesson);
    
    $date = date_create($dL[0]["date"]);
    $month = date_create($dL[0]["date"]);
    # Преобразовываем название месяца в русский текст
    $month = setRussianMonth(date_format($month,"n"), true);
    
    # Формируем конечную дату
    $dateLesson = date_format($date,"j ".$month." Y");
        // print_r($dateLesson);
        // exit();
print json_encode($dateLesson);
?>