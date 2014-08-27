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
$idT = $_POST["id"];
$dateL = $_POST["dateL"];
$year = date("Y", strtotime($dateL));
$week = ltrim(date("W", strtotime($dateL)));
$day = date("N", strtotime($dateL));

$Teacher = new Teacher($idT);
$dateForQuery = date("Y-m-d", strtotime($year."W".$week.$day))."%";
$lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsForTeacher, $Teacher->idTeacher, $dateForQuery);

//get last lesson number (needed for right css displaying)
$lastlesson = $lessonsByDayFromDb[count($lessonsByDayFromDb) - 1]["number"];
if($lessonsByDayFromDb){
    foreach ($lessonsByDayFromDb as $lessonByDayFromDb) {
        $idSubject = $lessonByDayFromDb["subjectS_id1"];
        $subjectInfo = $DB_DO->getRow(QSDO::$getSubjectById, $idSubject);
        if($lessonByDayFromDb["number"] == $lastlesson) { $islast = true; } else { $islast = false; }

        // get paragraphs of some lesson by id
        $paragraphsFromDb = $DB_DO->getAll(QSDO::$getLessonParagraphsTeacher, $lessonByDayFromDb["id"]);

        //get material by subgroup
        $idmaterial = $DB_DO->getOne(QSDO::$getMaterialByIdGroup, $lessonByDayFromDb["subgroupS_id1"]);

        // get classroom by id
        $classroomFromDB = $DB_DO->getRow(QSDO::$getClassroom, $lessonByDayFromDb["classroomS_id1"]);
        $numberclassroom = $classroomFromDB["number"];
        
        //get name class by subgroup
        $classFromDB = $DB_DO->getRow(QSDO::$getClassByIdGroup, $lessonByDayFromDb["subgroupS_id1"]);
        $nameClass = $classFromDB["name"];
        $idclass = $classFromDB["id"];

        //form array of paragraphs
        foreach($paragraphsFromDb as $paragraph) {

            // get partparagraphs of paragraphs, studied at this lesson
            $partparagraphsFromDb = $DB_DO->getAll(QSDO::$getLessonPartParagraphs, $lessonByDayFromDb["id"], $paragraph["id"]);
    //        var_dump($partparagraphsFromDb);
            foreach($partparagraphsFromDb as $partparagraph) {
                $partparagraphs[] = array(
                    "id" => $partparagraph["id"],
                    "number" => $partparagraph["number"]
                );
            }

            $paragraphs[] = array(
                "id" => $paragraph["id"],
                "number" => $paragraph["number"],
                "name" => $paragraph["name"],
                "notstudy" => $paragraph["notstudy"],
                "partparagraphs" => $partparagraphs    
            );
            unset($partparagraphs);
        }
        $lessons[$lessonByDayFromDb["number"]][$lessonByDayFromDb["subgroupS_id1"]] = array(
            "id" => $lessonByDayFromDb["id"],
            "number" => $lessonByDayFromDb["number"],
            "hometask" => $lessonByDayFromDb["hometask"],
            "cabinet" => $numberclassroom,
            "nameclass" => $nameClass,
            "idclass" => $idclass,
            "idmaterial" => $idmaterial,
            "color" => $subjectInfo["color"],
            "idsubject" => $idSubject,
            "subjectname" => $subjectInfo["name"],
            "islast" => $islast,
            "idsubgroup" => $lessonByDayFromDb["subgroupS_id1"],
            "paragraphs" => $paragraphs
        );
        unset($paragraphs);
    }
    for($i = 1; $i < intval($lastlesson); $i++){
        if($lessons[$i]){

        } else {
            $lessons[$i][0] = array(
                "idsubgroup" => 0,
                "cabinet" => "",
                "color" => "#e6e6e6",
                "subjectname" => "Нет урока",
                "idsubject" => 0,
                "islast" => false
            );
        }
    }
    ksort($lessons);
}
$response = $lessons;
print json_encode($response);
##############################################
?>
