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
if (isset ($_POST['textHometask']))   { $textHometask=$_POST['textHometask'];}
    if(mb_strlen($textHometask) > 256)
    	$textHometask = mb_substr($textHometask, 0, 256);
    $q = $DB_DO->query(QSDO::$addHometaskInLessonsTable, normalize(htmlspecialchars($textHometask)), $idLesson);
    
print json_encode($q);
?>