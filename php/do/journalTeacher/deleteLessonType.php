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
/***** AuthentificateProfile *****/     require PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/***** CONNECT TO DB_DO     ******/     require PROJECT_PATH.'/include/dbdo.php';
// /***** CONNECT TO DB_SOCIAL ******/     require PROJECT_PATH.'/include/dbsocial.php';

##############################################
if (isset ($_POST['idLesson']))   { $idLesson=$_POST['idLesson'];}
if (isset ($_POST['idSpisokLessonType']))   { $idSpisokLessonType=$_POST['idSpisokLessonType'];}
    
    if($DB_DO->getOne(QSDO::$checkCountLessonTypesCurrentLesson, $idLesson) > 1)
    	$result = $DB_DO->query(QSDO::$deleteLessonType, $idSpisokLessonType);
    else
    	$result = 'Error';
print json_encode($result);
?>