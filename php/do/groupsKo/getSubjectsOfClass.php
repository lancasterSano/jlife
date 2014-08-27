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
if(isset($_POST["c"])) {$idClass = $_POST["c"];}

// DECLARE VARIABLES NEEDED LATER
$resultarray = null;

// GET SUBGROUPS OF CLASS
$subgroupsFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idClass);
foreach ($subgroupsFromDB as $subgroupFromDB) {
    $idClass = $subgroupFromDB["classS_id1"];
    $idsubject = $subgroupFromDB["subjectS_id"];
    
    $resultarray[$idClass][$idsubject]["idsubject"] = $idsubject;
    $subjectFromDB = $DB_DO->getRow(QSDO::$getSubjectById, $idsubject);
    $namesubject = $subjectFromDB["name"];
    $resultarray[$idClass][$idsubject]["namesubject"] = $namesubject;
    
    $idTeacher = $subgroupFromDB["teacherS_id"];
    if($idTeacher) {
        $Teacher = new Teacher($idTeacher);
        $teacherFIO = $Teacher->FIO();
    } else {
        $teacherFIO = "Не назначен";
    }
    $resultarray[$idClass][$idsubject]["groups"][] = array(
        "idgroup" => $subgroupFromDB["id"],
        "namegroup" => $subgroupFromDB["name"],
        "TeacherFIO" => $teacherFIO,
        "TeacherID" => $idTeacher
    );
}

// FORM RESPONSE ARRAY
$response = $resultarray;

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
