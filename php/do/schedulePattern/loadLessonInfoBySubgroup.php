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

// объявим переменные
$linkedgroups = array();
$resultlesson = array();

// получим значения из POST
if(isset($_POST["l"])){$oldlesson = json_decode(stripslashes($_POST['l']), true);}
if(isset($_POST["g"])){$existinggroups = json_decode(stripslashes($_POST['g']), true);}
if(isset($_POST["s"])){$idNewSubgroup = $_POST['s'];}

// получим id школы по классу
$classFromDB = $DB_DO->getRow(QSDO::$getClass, $oldlesson["idClass"]);
$idschool = $classFromDB["schoolS_id"];

// получаем список всех связанных групп в школе
$linkedsubgroupsFromDB = $DB_DO->getAll(QSDO::$getLinkedGroupsOfSchool, $idschool);
foreach($linkedsubgroupsFromDB as $linkedsubgroupFromDB){
    $linkedgroups[$linkedsubgroupFromDB["idsubgroup1"]][] = $linkedsubgroupFromDB["idsubgroup2"];
}

if($idNewSubgroup == "0"){
    $isLessonUnset = true;
} else {
    $isLessonUnset = false;
}

if($linkedgroups[$idNewSubgroup]){
    $isNewGroupLinked = true;
} else {
    $isNewGroupLinked = false;
}
if($oldlesson["isLinked"] == "0"){
    $isOldGroupLinked = false;
} else {
    $isOldGroupLinked = true;
}

if(count($existinggroups) == 1){
    if($oldlesson["idsubgroup"] == $existinggroups[0]){
        $isLessonLast = true;
    } else {
        $isLessonLast = false;
    }
}

if($isOldGroupLinked){
    if($isLessonUnset){
        if($isLessonLast) {
            $resultlesson = array(
                "idClass" => $oldlesson["idClass"],
                "dayNumber" => $oldlesson["dayNumber"],
                "number" => $oldlesson["number"],
                "idsubgroup" => 0,
                "isLinked" => 0, 
                "status" => "window",
                "action" => "unsetLinkedLast",
                "countsubgrouptodelete" => count($linkedgroups[$oldlesson["idsubgroup"]])
            );
        } else {
            $resultlesson = array(
                "idClass" => $oldlesson["idClass"],
                "dayNumber" => $oldlesson["dayNumber"],
                "number" => $oldlesson["number"],
                "idsubgroup" => 0,
                "isLinked" => 1, 
                "status" => "window",
                "action" => "unsetLinkedNotLast"
            );
        }
    } else {
        $subgroupInfoFromDB = $DB_DO->getRow(QSDO::$getSubgroup, $idNewSubgroup);
        $teachersID = $subgroupInfoFromDB["teacherS_id"];
        $Teacher = new Teacher($teachersID);
        $subjectsID = $subgroupInfoFromDB["subjectS_id"];
        $subjectFromDB = $DB_DO->getRow(QSDO::$getSubjectById, $subjectsID);
        $counthours = $subjectFromDB["counthoursfirstsem"]."ч";
        $resultlesson = array(
            "idClass" => $oldlesson["idClass"],
            "dayNumber" => $oldlesson["dayNumber"],
            "number" => $oldlesson["number"],
            "idsubgroup" => $idNewSubgroup,
            "isLinked" => 1, 
            "status" => "lesson",
            "action" => "setLinkedFromLinked",
            "hours" => $counthours,
            "teachersFIO" => $Teacher->FIOInitials()
        );
    }
} else {
    if($isLessonUnset){
        $resultlesson = array(
            "idClass" => $oldlesson["idClass"],
            "dayNumber" => $oldlesson["dayNumber"],
            "number" => $oldlesson["number"],
            "idsubgroup" => 0,
            "isLinked" => 0, 
            "status" => "window",
            "action" => "unsetUnlinked"
        );
    } else {
        $subgroupInfoFromDB = $DB_DO->getRow(QSDO::$getSubgroup, $idNewSubgroup);
        $teachersID = $subgroupInfoFromDB["teacherS_id"];
        $Teacher = new Teacher($teachersID);
        $subjectsID = $subgroupInfoFromDB["subjectS_id"];
        $subjectFromDB = $DB_DO->getRow(QSDO::$getSubjectById, $subjectsID);
        $counthours = $subjectFromDB["counthoursfirstsem"]."ч";
        if($isNewGroupLinked){
            $resultlesson = array(
                "idClass" => $oldlesson["idClass"],
                "dayNumber" => $oldlesson["dayNumber"],
                "number" => $oldlesson["number"],
                "idsubgroup" => $idNewSubgroup,
                "isLinked" => 1, 
                "status" => "lesson",
                "action" => "setLinkedFromUnlinked",
                "hours" => $counthours,
                "teachersFIO" => $Teacher->FIOInitials(),
                "countsubgrouptoadd" => count($linkedgroups[$idNewSubgroup])
            );
        } else {
            $resultlesson = array(
                "idClass" => $oldlesson["idClass"],
                "dayNumber" => $oldlesson["dayNumber"],
                "number" => $oldlesson["number"],
                "idsubgroup" => $idNewSubgroup,
                "isLinked" => 0, 
                "status" => "lesson",
                "action" => "setUnlinkedFromUnlinked",
                "hours" => $counthours,
                "teachersFIO" => $Teacher->FIOInitials(),
            );
        }
    }
}

// main

//$response = array("isOldLinked" => $isOldGroupLinked, "isNewLinked" => $isNewGroupLinked);
$response = $resultlesson;

// send response to client
print json_encode($response);
?>
