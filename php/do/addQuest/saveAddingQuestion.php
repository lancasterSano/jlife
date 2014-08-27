<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/y/QueryStorageY.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
/****** CONNECT TO DB_Y ******/ require_once PROJECT_PATH.'/include/dby.php';

##############################################

if (isset ($_POST['text']))   { $text=$_POST['text'];}
if (isset ($_POST['idPartParagraph']))   { $idPartParagraph=$_POST['idPartParagraph'];}
if (isset ($_POST['complexity']))   { $complexity = $_POST['complexity'];}
    	
    	/****** Добавляем вопрос в таблицу @questions *****/
    	// $q = $DB_DO->query(QSDO::$addQuestion, $text, $idPartParagraph, $complexity);
    	$q = $DB_DO->query("CALL cQuestionPartparagraph(?s,?i,?i,@idQuestion);", $text, $complexity, $idPartParagraph);
        // print_r($q);
        $idQuestion = $DB_DO->getOne("SELECT @idQuestion as idQuestion"); 
    	/** И тут же получаем id только что добавленного ответа**/
    	// $newAnswerId = $DB_DO->insertId();
    	/********************************************************/


    	/*** Вызов процедуры проверки valid в таблице questions ***/
    	// $q = $DB_DO->query(QSDO::$checkValidQuestion, $idQuestion);
    	/**********************************************************/

    	// /******* Получаем значение valid из таблицы questions *******/
    	// $valid = $DB_DO->getOne(QSDO::$getValidQuestion, $idQuestion);
    	// /************************************************************/

print json_encode($idQuestion);
?>