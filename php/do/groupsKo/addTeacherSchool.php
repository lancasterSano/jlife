<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

// GET VALUES FROM GET
if(isset($_POST["idSoc"])) {$idSocial = $_POST["idSoc"];}
if(isset($_POST["idSch"])) {$idSchool = intval($_POST["idSch"]);}

// DECLARE VARIABLES NEEDED LATER
 
// MAIN PART
$isTeacherInSchool = $DB->getOne(QS::$checkTeacherInSchool, $idSocial, $idSchool);
if(!$isTeacherInSchool){
    $profileFromDB = $DB->getRow(QS::$q3, $idSocial);
    $Profile = new Profile($idSocial, $profileFromDB);
    $r = $DB_DO->query(QSDO::$cTeacher, $Profile->ID, $Profile->FirstName, $Profile->LastName, $Profile->MiddleName, $idSchool, 1);
    $idTeacher = $DB_DO->getOne("SELECT @idTeacher");
    $schoolFromDB = $DB_DO->getRow(QSDO::$getSchool, $idSchool);
    $r = $DB->query(QS::$cRole, $Profile->ID, 2, $idTeacher, $idSchool, "Учитель", $schoolFromDB["name"]);
    $teacherFromDB = $DB_DO->getRow(QSDO::$getTeacher, $idTeacher);
    $category = $teacherFromDB["category"];
}

// FORM RESPONSE ARRAY
$response = array(
    "id" => $idTeacher,
    "category" => $category,
    "firstname" => $Profile->FirstName,
    "lastname" => $Profile->LastName,
    "middlename" => $Profile->MiddleName,
    "isTeacherInSchool" => $isTeacherInSchool);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>