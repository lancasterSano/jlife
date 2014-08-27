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
if(isset($_POST["g"])) {$idgroup = $_POST["g"];}
if(isset($_POST["to"])) {$idoldteacher = $_POST["to"];}
if(isset($_POST["tn"])) {$idnewteacher = $_POST["tn"];}
if(isset($_POST["s"])) {$idsubject = $_POST["s"];}

// DECLARE VARIABLES NEEDED LATER
//
//echo "ID SUBJECT - ".$idsubject."<br>";
//echo "ID OLD TEACHER - ".$idoldteacher."<br>";
//echo "ID NEW TEACHER - ".$idnewteacher."<br>";
if($idnewteacher == "0") {
    $idnewteacher = NULL;
    $teacherFIO = "Не назначен";
} else {
    $newTeacher = new Teacher($idnewteacher);
    $teacherFIO = $newTeacher->FIO();
}
// GET OLD AND NEW TEACHERSUBJECTS ID
$oldTeacherSubjectFromDB = $DB_DO->getRow(QSDO::$getTeacherSubjectsID, $idoldteacher, $idsubject);
//echo "from db - ".$oldTeacherSubjectFromDB."<br>";
$oldTeacherSubjectsID = $oldTeacherSubjectFromDB["id"];
//echo "id teachersubjects of old teacher - ".$oldTeacherSubjectsID."<br>";
if($idnewteacher) {
//    echo "idteacher is not null";
    $newTeacherSubjectFromDB = $DB_DO->getRow(QSDO::$getTeacherSubjectsID, $idnewteacher, $idsubject);
    $newTeacherSubjectsID = $newTeacherSubjectFromDB["id"];
//    echo "id teachersubjects of new teacher - ".$newTeacherSubjectsID."<br>";
}

// GET NAME OF GROUP
$subgroupFromDB = $DB_DO->getRow(QSDO::$getSubgroup, $idgroup);
$nameGroup = $subgroupFromDB["name"];
//echo "name of group - ".$nameGroup."<br>";

// UPDATE SUBGROUPS TEACHER
$r = $DB_DO->query(QSDO::$updateSubgroupsTeacher, $idnewteacher, $idgroup);

// GET ID IN SPISOKTEACHERSUBJECTGROUP BY ID TEACHERSUBJECT AND ID GROUP
$r = $DB_DO->getRow(QSDO::$getIDOfCurrentTeachingSubject, $oldTeacherSubjectsID, $idgroup);
$spisokTeacherSubjectGroupID = $r["id"];
//echo "ID SPISOK TEACHER SUBJECT GROUP -".$spisokTeacherSubjectGroupID."<br>";

// SAVE TO HISTORY TIME OF TEACHING WITH OLD TEACHER
$r = $DB_DO->query(QSDO::$saveTeachingSubjectToHistory, date("Y-m-d"), $spisokTeacherSubjectGroupID);

if($idnewteacher){
    // ADD NEW ROW TO SPISOKTEACHERSUBJECTS
    $r = $DB_DO->query(QSDO::$setNewTeacherToGroup, date("Y-m-d"), $idgroup, $newTeacherSubjectsID);
}

// FORM RESPONSE ARRAY
$response = array("teacherFIO" => $teacherFIO, "namegroup" => $nameGroup);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>