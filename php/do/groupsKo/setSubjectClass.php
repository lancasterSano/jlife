<?php
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

// GET VALUES FROM GET
if(isset($_POST["c"])) {$idClass = $_POST["c"];}
if(isset($_POST["t"])) {$idTeacher = $_POST["t"];}
if(isset($_POST["s"])) {$idSubject = $_POST["s"];}

// DECLARE VARIABLES NEEDED LATER


// SET SUBJECTS TO CLASS
if($idTeacher == "null"){
    $r = $DB_DO->query(QSDO::$setSubjectClass, $idClass, $idSubject, NULL);
} else {
    $r = $DB_DO->query(QSDO::$setSubjectClass, $idClass, $idSubject, $idTeacher);
}
$r = $DB_DO->getRow(QSDO::$getSubgroupOfNewClass, $idClass, $idSubject);
$idgroup = $r["id"];

// ADD SUBGROUP TO Y
$classFromDB = $DB_DO->getRow(QSDO::$getClass, $idClass);
$idschool = $classFromDB["schoolS_id"];
$schoolratioFromDB = $DB_DO->getRow(QSDO::$getSchoolRatio, $idschool);
if($schoolratioFromDB){
    $subjectSchoolFromDB = $DB_DO->getRow(QSDO::$getSubjectInSchool, $idschool, $idSubject);
    $absolutecomplexity = $subjectSchoolFromDB["levelsr"];
    $kpriority = $subjectSchoolFromDB["kpriority"];

    $kreviewlag = $schoolratioFromDB["kreviewlag"];
    $kactivestudydurationlag = $schoolratioFromDB["kactivestudydurationlag"];
    $kreviewactual = $schoolratioFromDB["kreviewactual"];
    $kactivestudydurationactual = $schoolratioFromDB["kactivestudydurationactual"];
    $kactivesectionactual = $schoolratioFromDB["kactivesectionactual"];
    $kreviewadvance = $schoolratioFromDB["kreviewadvance"];
    $kactivestudydurationadvance = $schoolratioFromDB["kactivestudydurationadvance"];
    $kactivesectionadvance = $schoolratioFromDB["kactivesectionadvance"];
    $kexternadvance = $schoolratioFromDB["kexternadvance"];
    $kteacherpopular = $schoolratioFromDB["kteacherpopular"];
    $kteacherunpopular = $schoolratioFromDB["kteacherunpopular"];
    $kborrowedpopular = $schoolratioFromDB["kborrowedpopular"];
    $kborrowedunpopular = $schoolratioFromDB["kborrowedunpopular"];
    $r = $DB_Y->query(QSY::$createSubgroup, $idgroup, $absolutecomplexity, $idschool, $idSubject, $kpriority,
            $kreviewlag, $kactivestudydurationlag, $kreviewactual, $kactivestudydurationactual, $kactivesectionactual, 
            $kreviewadvance, $kactivestudydurationadvance, $kactivesectionadvance, $kexternadvance,
            $kteacherpopular, $kteacherunpopular, $kborrowedpopular, $kborrowedunpopular);
}

// FORM RESPONSE ARRAY
$response = array("idgroup" => $idgroup, "other" => "");

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>