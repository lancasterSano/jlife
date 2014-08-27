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
if(isset($_POST["s"])) {$idschool = $_POST["s"];}

// DECLARE VARIABLES NEEDED LATER
//$resultarray = null;

// GET SUBGROUPS OF CLASS
$subjectsFromDB = $DB_DO->getAll(QSDO::$getSubjectsSchool, $idschool);
$resultarray = $subjectsFromDB;

// FORM RESPONSE ARRAY
$response = $resultarray;

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
