<?php
# Version 1.0
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

##############################################
$currentSelectedId = $_POST["id"];
$currentSelectedName = $_POST["n"];
$idKo = $_POST["idK"];
$dateL = $_POST["dateL"];
$type = $_POST["type"];
$year = date("Y", strtotime($dateL));
$week = ltrim(date("W", strtotime($dateL)));
$day = date("N", strtotime($dateL));


$Ko = new Ko($idKo);
$dateForQuery = date("Y-m-d", strtotime($year."W".$week.$day))."%";

switch($type){
    case null:
    case 1:
        // GET SUBGROUPS OF CLASS
        $classsubgroupsFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClassIDs, $currentSelectedId);
        if($classsubgroupsFromDB) {
            foreach ($classsubgroupsFromDB as $value) {
                $classsubgroups[] = $value["id"];
            }

            // GET LESSONS OF CLASS
            $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsArray, $dateForQuery, $classsubgroups);
        }
        unset($classsubgroups);
        break;
    case 2:
        // GET LESSONS OF TEACHER
        $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsForTeacher, $currentSelectedId, $dateForQuery);
        break;
    case 3:
        // GET LESSONS OF SUBJECTNAME
//                print_r($currentSelectedName);
        $subjectIDsFromDB = $DB_DO->getAll(QSDO::$getSubjectIDsByName, $Ko->idSchool, $currentSelectedName);
        foreach($subjectIDsFromDB as $value) {
            $subjectIDs[] = $value["id"];
        }
//                print_r($subjectIDs);
        $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsBySubjectname, $dateForQuery, $subjectIDs);
        unset($subjectIDs);
        break;
    case 4:
        // GET LESSONS IN CABINET
        $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsByCabinet, $dateForQuery, $currentSelectedId);
        break;
    default:
}


//get last lesson number (needed for right css displaying)
$lastlesson = $lessonsByDayFromDb[count($lessonsByDayFromDb) - 1]["number"];

if($lessonsByDayFromDb) {
    foreach ($lessonsByDayFromDb as $lessonByDayFromDb) {
        $idSubject = $lessonByDayFromDb["subjectS_id1"];
        $subjectInfo = $DB_DO->getRow(QSDO::$getSubjectById, $idSubject);
        if($lessonByDayFromDb["number"] == $lastlesson) { $islast = true; } else { $islast = false; }

        $classFromDb = $DB_DO->getRow(QSDO::$getClassByIdGroup, $lessonByDayFromDb["subgroupS_id1"]);
        $subgroupFromDB = $DB_DO->getRow(QSDO::$getSubgroup, $lessonByDayFromDb["subgroupS_id1"]);
        $countownsubgroup = $subgroupFromDB["countownsubgroup"];
        if($countownsubgroup == 1) {
            $nameclassgroup = $classFromDb["name"];
        } else {
            $nameclassgroup = $classFromDb["name"]." ".$subgroupFromDB["name"];
        }
        $lessontypes = array();
        $spisoklessontypesFromDB = $DB_DO->getAll(QSDO::$getLessonsTypeLesson, $lessonByDayFromDb["id"]);
        $lastlessontypeID = $spisoklessontypesFromDB[count($spisoklessontypesFromDB) - 1]["lessontypeS_id"];
        foreach ($spisoklessontypesFromDB as $value) {
            $idLessonType = $value["lessontypeS_id"];
            if($idLessonType == $lastlessontypeID) {$islastlessontype = 1;}
            else {$islastlessontype = 0;}
            $lessontypeFromDb = $DB_DO->getRow(QSDO::$getLessontype, $idLessonType);

            $lessontypes[] = array(
                "id" => $idLessonType,
                "name" => $lessontypeFromDb["name"],
                "description" => $lessontypeFromDb["description"],
                "islastlessontype" => $islastlessontype
            );
        }

        $Teacher = new Teacher($lessonByDayFromDb["teacherS_id1"]);

        $lessons[$lessonByDayFromDb["number"]][$lessonByDayFromDb["subgroupS_id1"]] = array(
            "id" => $lessonByDayFromDb["id"],
            "number" => $lessonByDayFromDb["number"],
            "hometask" => $lessonByDayFromDb["hometask"],
            "cabinet" => $lessonByDayFromDb["classroomS_id1"],
            "idsubgroup" => $lessonByDayFromDb["subgroupS_id1"],
            "idteacher" => $Teacher->idTeacher,
            "fioteacher" => $Teacher->FIOInitials(),
            "color" => $subjectInfo["color"],
            "idsubject" => $idSubject,
            "nameclassgroup" => $nameclassgroup,
            "subjectname" => $subjectInfo["name"],
            "islast" => $islast,
            "lessontypes" => $lessontypes
        );
        unset($lessontypes);
    }
}
for($i = 1; $i < intval($lastlesson); $i++){
    if($lessons[$i]){

    } else {
        $lessons[$i][0] = array("idsubgroup" => 0, "cabinet" => "", "color" => "#e6e6e6", "subjectname" => "Нет урока");
    }
}
ksort($lessons);

$response = $lessons;
print json_encode($response);
##############################################
?>
