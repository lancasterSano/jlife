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
if(isset($_POST["c"])) {$idclass = $_POST["c"];}
if(isset($_POST["s"])) {$idsubject = $_POST["s"];}
if(isset($_POST["t"])) {$idteacher = $_POST["t"];}
if(isset($_POST["cos"])) {$countownsubgroup = $_POST["cos"];}

if($idteacher == "0") {
    $idteacher = NULL;
}
// DECLARE VARIABLES NEEDED LATER

// ADD SUBGROUP TO DB
$r = $DB_DO->query(QSDO::$createSubgroup, $idclass, $idsubject, 0, $idteacher, $countownsubgroup);
$r = $DB_DO->getRow("SELECT @idgroup as id;");
$idgroup = $r["id"];

// ADD SUBGROUP TO Y
$classFromDB = $DB_DO->getRow(QSDO::$getClass, $idclass);
$idschool = $classFromDB["schoolS_id"];
$schoolratioFromDB = $DB_DO->getRow(QSDO::$getSchoolRatio, $idschool);
if($schoolratioFromDB){
    $subjectSchoolFromDB = $DB_DO->getRow(QSDO::$getSubjectInSchool, $idschool, $idsubject);
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
    $r = $DB_Y->query(QSY::$createSubgroup, $idgroup, $absolutecomplexity, $idschool, $idsubject, $kpriority,
            $kreviewlag, $kactivestudydurationlag, $kreviewactual, $kactivestudydurationactual, $kactivesectionactual, 
            $kreviewadvance, $kactivestudydurationadvance, $kactivesectionadvance, $kexternadvance,
            $kteacherpopular, $kteacherunpopular, $kborrowedpopular, $kborrowedunpopular);
}
$groupFromDB = $DB_DO->getRow(QSDO::$getSubgroup, $idgroup);
$namegroup = $groupFromDB["name"];
    
if($idteacher) {
    
    // GET TEACHERSUBJECTS ID
    $teacherSubjectFromDB = $DB_DO->getRow(QSDO::$getTeacherSubjectsID, $idteacher, $idsubject);
    $teacherSubjectsID = $teacherSubjectFromDB["id"];
    
    // ADD NEW ROW TO SPISOKTEACHERSUBJECTS
    $r = $DB_DO->query(QSDO::$setNewTeacherToGroup, date("Y-m-d"), $idgroup, $teacherSubjectsID);
    
    // CREATE TEACHER OBJECT
    $Teacher = new Teacher($idteacher);
    $FIOteacher = $Teacher->FIO();
} else {
    $idteacher = "0";
    $FIOteacher = "Не назначен";
}

// FORM RESPONSE ARRAY
$response = array("idgroup" => $idgroup, "namegroup" => $namegroup, "IDteacher" => $idteacher, "FIOteacher" => $FIOteacher);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>