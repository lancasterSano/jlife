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

// DECLARE VARIABLES NEEDED LATER
$teachers = null;

// GET VALUES FROM GET
if(isset($_POST["s"])) {$idsubject = intval($_POST["s"]);}
if(isset($_POST["sch"])) {$idschool = intval($_POST["sch"]);}

// GET TEACHERS OF CURRENT SUBJECT
$teachersFromDB = $DB_DO->getAll(QSDO::$getTeachersWhoCanTeachSubject, $idsubject, $idschool);
foreach ($teachersFromDB as $teacherFromDB) {
    $idteacher = $teacherFromDB["id"];
    $Teacher = new Teacher($idteacher);
    $teachers[$idteacher]["id"] = $idteacher;
    $teachers[$idteacher]["name"] = $Teacher->FIOInitials();
    $teachers[$idteacher]["fullname"] = $Teacher->FIO();
}

// FORM RESPONSE ARRAY
$response = $teachers;

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
