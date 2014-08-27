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

// объявим переменные
$dates = array();
$lessons = array();
$result = array();
$sections = array();
$subgroupsForY = array();
$lessonsSubgroup = array();

// получим значения из POST
if(isset($_POST["s"])){$idSchool = $_POST["s"];}

// main

// узнаем информационные данные о семестре
$r = $DB_DO->getRow(QSDO::$getActualStudyDuration, $idSchool);
$isLessons = $r["isLessons"];
if(!$isLessons){
    $START_EDUCATION_DATE = $r["begin"];
    $END_EDUCATION_DATE = $r["end"];
    $NAME_DURATION = $r["name"];

    // узнаем количество рабочих дней
    $r = $DB_DO->getRow(QSDO::$getCountStudyDays, $idSchool);
    $COUNT_WORK_DAYS = $r["countstudyday"];

    // формируем массив дат для генерации расписания
    $curDateTimeString = $START_EDUCATION_DATE;
    $curDateTime = strtotime($START_EDUCATION_DATE);
    $dayNumber = date("N", $curDateTime);
    if(date("N", $curDateTime) <= intval($COUNT_WORK_DAYS)){
        $dates[$dayNumber][] = $curDateTimeString;
    }

    while($curDateTimeString != $END_EDUCATION_DATE){
        $nextDateTime = strtotime($curDateTimeString." +1 day");
        $curDateTimeString = date("Y-m-d", $nextDateTime);
        $dayNumber = date("N", $nextDateTime);
        if(date("N", $nextDateTime) <= intval($COUNT_WORK_DAYS)){
            $dates[$dayNumber][] = $curDateTimeString;
        }
    }
    // получаем шаблоны уроков для всей школы и 
    // сохраняем в массив $lessons 
    $lessonsFromDB = $DB_DO->getAll(QSDO::$getTimetableSchool, $idSchool);
    foreach ($lessonsFromDB as $lessonFromDB) {
        $lessons[$lessonFromDB["dayweek"]][$lessonFromDB["classs_id"]][] = $lessonFromDB;
    }
    
    $classesSchoolFromDB = $DB_DO->getAll(QSDO::$getClassesSchool, $idSchool);
    foreach($classesSchoolFromDB as $class){
        $idclass = $class["id"];
        $subgroupsFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idclass);
        if($subgroupsFromDB){
            foreach($subgroupsFromDB as $subgroupFromDB){
                $idsubgroup = $subgroupFromDB["id"];
                $idteacher = $subgroupFromDB["teacherS_id"];
                $idsubject = $subgroupFromDB["subjectS_id"];
                $subgroup = array(
                    "idteacher" => $idteacher,
                    "idsubject" => $idsubject
                );
                $subgroupsclasses[$idclass][$idsubgroup] = $subgroup;
                

            }
        } else {
            $subgroupsclasses[$idclass] = array();
        }
    }
    
    
    foreach($lessonsFromDB as $lessonFromDB) {
        $dayNumber = $lessonFromDB["dayweek"];
        $idsubgroup = $lessonFromDB["subgroupS_id1"];
        $subgroup = $subgroupsclasses[$lessonFromDB["classs_id"]][$idsubgroup];
        $idteacher = $subgroup["idteacher"];
        $idsubject = $subgroup["idsubject"];
        $number = $lessonFromDB["number"];
        $idclassroom = $lessonFromDB["classroomS_id1"];
        foreach($dates[$dayNumber] as $date){
            $resultdate = $date." ".$lessonFromDB["timestart"];
            $DB_DO->query(QSDO::$createLesson, $idsubgroup, $idsubject, $idteacher, $idclassroom, $number, $resultdate);
            $r = $DB_DO->getRow("SELECT @idLesson as id;");
            $idlesson = $r["id"];
            $r = $DB_DO->query(QSDO::$addNewLessonType, $idlesson, 1);
        }
        // Сформировать список валидных групп
        $subgroupsForYPrep[$idsubgroup] = $subgroup;
    }
    # пройтись по группам в цикле и выполнить это
    foreach ($subgroupsForYPrep as $idsubgroup => $subgroup) {
        // DATA FOR Y
        $sectionsSubgroupFromDB = $DB_DO->getAll("SELECT `id`, `number`, `counthours` 
            FROM `sections` WHERE `subjectS_id1` = ?i AND `deleted` = 0 ORDER BY `number`", $subgroup["idsubject"]);
        foreach ($sectionsSubgroupFromDB as $sectionSubgroupFromDB) {
            $idsection = $sectionSubgroupFromDB["id"];
            $counthours = $sectionSubgroupFromDB["counthours"];
            $number = $sectionSubgroupFromDB["number"];
            $sections[$idsection]["counthours"] = $counthours;
            $sections[$idsection]["number"] = $number;
        }
        $subgroupsForY[$idsubgroup]["sections"] = $sections;
        $subgroupsForY[$idsubgroup]["subjectID"] = $subgroupsForYPrep[$idsubgroup]["idsubject"];
        unset($sections);
    }
    
    # FORM TABLE IN Y #
    foreach ($subgroupsForY as $idsubgroupForY => $subgroupForY) {
        $lessonsSubgroupFromDB = $DB_DO->getAll("SELECT * FROM `lessons` WHERE `subgroupS_id1` = ?i AND `date` BETWEEN ?s AND ?s AND `deleted` = 0 ORDER BY `date`",
                $idsubgroupForY, $START_EDUCATION_DATE, $END_EDUCATION_DATE);
        $i = 1;
        foreach ($lessonsSubgroupFromDB as $lessonSubgroupFromDB) {
            $lessonsSubgroup[$i] = $lessonSubgroupFromDB["date"];
            $i++;
        }
        $subgroupsForY[$idsubgroupForY]["lessons"] = $lessonsSubgroup;
        unset($lessonsSubgroup);
    }
    foreach ($subgroupsForY as $idsubgroup => $subgroupForY) {
        $addedFirstSection = false;
        $prevhours = 1;
        if($subgroupForY["sections"]){
            foreach($subgroupForY["sections"] as $idsection => $section){
                $IDsubgroupY = $DB_Y->getOne(QSY::$getSubgroupYIDFromSubgroupDOID, $idsubgroup);
                if($IDsubgroupY){
                    $dateOpenSection = $subgroupsForY[$idsubgroup]["lessons"][$prevhours];
                    if($dateOpenSection){
                        $r = $DB_Y->query(QSY::$addSectionSubgroupAvalaible, $idsection, $section["number"], $IDsubgroupY, $subgroupForY["subjectID"], $dateOpenSection);
                        $addedFirstSection = true;
                        if($addedFirstSection){
                            $prevhours = $prevhours + $section["counthours"];
                        }
                    }
                }
            }
        }
    }
    # END FORMING TABLE IN Y #
    
    $DB_DO->query(QSDO::$updateIsTimetableGenerated, $idSchool);
    

    // сформировать результирующий массив
    $response = array(
        "status" => "generatedOK",
        "subgroups" => $subgroupsForY 
    );
} else {
    $response = array(
        "status" => "generatedAlreadyException"
    );
}
// send response to client
print json_encode($response);
?>