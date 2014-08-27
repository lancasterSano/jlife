<?php
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

// GET VALUES FROM GET
if(isset($_POST["t"])) {$idteacher = $_POST["t"];}
if(isset($_POST["n"])) {$namesubject = $_POST["n"];}
if(isset($_POST["l"])) {$level = $_POST["l"];}
if(isset($_POST["c"])) {$complexity = $_POST["c"];}

// DECLARE VARIABLES NEEDED LATER

// GET IDSUBJECT BY NAME AND LEVEL AND IDSCHOOL
$subjectFromDB = $DB_DO->getRow(QSDO::$getSubjectByNameAndLevelAndComplexity, $namesubject, $level, $complexity);
$idsubject = $subjectFromDB["id"];

// SET SUBJECT TO TEACHER
$r = $DB_DO->query(QSDO::$setTeacherSubject, $idteacher, $idsubject);

// FORM RESPONSE ARRAY
$response = $idsubject;

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
