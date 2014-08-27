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
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dby.php';

##############################################

if (isset ($_POST['idAnswer']))   { $idAnswer=$_POST['idAnswer'];}
if (isset ($_POST['idQuestion']))   { $idQuestion=$_POST['idQuestion'];}
    	
    	/********** Обновляем состояние right в таблице answers ************/
    	// $q = $DB_DO->query(QSDO::$updateRightFalseAnswer, $idAnswer);
    	$q = $DB_DO->query("CALL switchRightIncorrectAnswer(?i,?i)", $idAnswer, $idQuestion);
    	/*******************************************************************/

    	// /*** Вызов процедуры проверки valid в таблице questions ***/
    	// $q = $DB_DO->query(QSDO::$checkValidQuestion, $idQuestion);
    	// /**********************************************************/

    	/******* Получаем значение valid из таблицы questions *******/
    	$valid = $DB_DO->getOne(QSDO::$getValidQuestion, $idQuestion);
    	/************************************************************/
        if($valid == "1"){
            $questionY= $DB_Y->getRow(QSY::$getQuestionTextY, $idQuestion);
            if(!$questionY){
                $questionFromDB = $DB_DO->getRow(QSDO::$getQuestion, $idQuestion);
                $idPartParagraph = $questionFromDB["partparagraphS_id1"];
                $complexity = $questionFromDB["complexity"];
                $partParagraphFromDB = $DB_DO->getRow(QSDO::$getPartParagraph, $idPartParagraph);
                $idParagraph = $partParagraphFromDB["paragraphS_id1"];
                $paragraphFromDB = $DB_DO->getRow(QSDO::$getParagraph, $idParagraph);
                $idSection = $paragraphFromDB["sectionS_id1"];
                $sectionFromDB = $DB_DO->getRow(QSDO::$getSection, $idSection);
                $idsubject = $sectionFromDB["subjectS_id1"];
                $idMaterial = $paragraphFromDB["materialS_id1"];
                $material = new Material($idMaterial);
                $teacher = new Teacher($material->idTeacher);
                $idschool = $teacher->idSchool;
                $subjectSchoolFromDB = $DB_DO->getRow(QSDO::$getSubjectInSchool, $idschool, $idsubject);
                $absolutecomplexity = $subjectSchoolFromDB["levelsr"];
                $r = $DB_Y->query(QSY::$addQuestionMaterial, $material->idTeacher, $idQuestion, 
                        $idParagraph, $idSection, $idMaterial, $complexity, $absolutecomplexity, $idsubject);
            }
        }

print json_encode($valid);
?>