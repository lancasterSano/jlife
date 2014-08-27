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

if (isset ($_POST['idAnswer']))   { $idAnswer=$_POST['idAnswer'];}
if (isset ($_POST['idQuestion']))   { $idQuestion=$_POST['idQuestion'];}

    	/*** Удаляем вариант ответа из таблицы @answers ***/
    	// $q = $DB_DO->query(QSDO::$deleteAnswer, $idAnswer);
    	$q = $DB_DO->query("CALL dAnswerOne(?i)", $idAnswer);
    	/**************************************************/
    	// $newAnswerId = $DB_DO->insertId();

    	/******* Получаем значение valid из таблицы @questions ******/
    	$valid = $DB_DO->getOne(QSDO::$getValidQuestion, $idQuestion);
    	/************************************************************/

print json_encode($valid);
?>