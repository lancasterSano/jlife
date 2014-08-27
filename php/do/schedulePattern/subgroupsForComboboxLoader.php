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
$subjectsSchool = array();
$subgroups = array();
$linkedgroups = array();

// получим значения из POST
if(isset($_POST["l"])){$lesson = json_decode(stripslashes($_POST['l']), true);}
if(isset($_POST["g"])){$subgroupsFromJS = json_decode(stripslashes($_POST['g']), true);}
if(isset($_POST["c"])){$idClass = $_POST['c'];}

$classFromDB = $DB_DO->getRow(QSDO::$getClass, $idClass);
$level = $classFromDB["level"];
$idschool = $classFromDB["schoolS_id"];

// найдем все группы этого класса
$subgroupsClassFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idClass);
if($subgroupsClassFromDB){
    // получаем список предметов конкретной школы
    $subjectsSchoolFromDB = $DB_DO->getAll(QSDO::$getSubjectsBySchoolAndLevel, $idschool, $level);
    foreach ($subjectsSchoolFromDB as $subjectSchoolFromDB) {
        $subjectsSchool[$subjectSchoolFromDB["id"]] = $subjectSchoolFromDB;
    }
    
    // получаем список всех связанных групп в школе
    $linkedsubgroupsFromDB = $DB_DO->getAll(QSDO::$getLinkedGroupsOfSchool, $idschool);
    foreach($linkedsubgroupsFromDB as $linkedsubgroupFromDB){
        $linkedgroups[$linkedsubgroupFromDB["idsubgroup1"]][] = $linkedsubgroupFromDB["idsubgroup2"];
    }
    
    // сформируем массив сабгрупп, объединённый с предметами
    foreach($subgroupsClassFromDB as $subgroupClassFromDB){
        $tempString = $subjectsSchool[$subgroupClassFromDB["subjectS_id"]]["name"];
        if(mb_strlen($tempString) > 16){
            $nameSubject = mb_substr($tempString, 0, 15).".";
        } else {
            $nameSubject = $tempString;
        }
        if($subgroupClassFromDB["countownsubgroup"] > 1){
            $nameSubject = $nameSubject." ".preg_replace('/^\D+/','', $subgroupClassFromDB["name"]);
        }
        $subgroup = array(
            "id" => $subgroupClassFromDB["id"],
            "subject" => $subjectsSchool[$subgroupClassFromDB["subjectS_id"]],
            "linkedgroups" => $linkedgroups[$subgroupClassFromDB["id"]],
            "name" => $subgroupClassFromDB["name"],
            "countownsubgroup" => $subgroupClassFromDB["countownsubgroup"],
            "subjectname" => $nameSubject
        );
        $subgroups[$subgroupClassFromDB["id"]] = $subgroup;
        unset($subgroup);
    }
    
    switch($lesson["status"]){
        case "noLessonsDay":
        case "window":
            if($lesson["isLinked"]) {
                
                // находим первую попавшуюся группу в этот день на этом уроке у этого класса
                $idFirstLinkedLessonSubgroup = $subgroupsFromJS[0];
//                
                // находим связанные группы этой первой попавшейся группы
                $linkedSubgroupsLesson = $linkedgroups[$idFirstLinkedLessonSubgroup];
//                
                // для каждой связанной группы находим урок из массива
                foreach($subgroups as $subgroup){
                    foreach($linkedSubgroupsLesson as $linkedSubgroup){
                        if($subgroup["id"] == $linkedSubgroup || 
                           $subgroup["id"] == $idFirstLinkedLessonSubgroup){
                            
                        } else {
                            unset($subgroups[$subgroup["id"]]);
                        }
                    }
                }
                
                if($subgroupsFromJS) {
                    // убираем те уроки, которые уже есть в расписании JS (чтобы в комбобоксе они не отображались)
                    foreach($subgroups as $idSubgroup => $subgroup){
                        foreach($subgroupsFromJS as $idsubgroupJS){
                            if($idSubgroup == $idsubgroupJS){
                                unset($subgroups[$idSubgroup]);
                            }
                        }
                    }
                }
                $response = array(
                    "subgroups" => $subgroups, 
                    "currentSubgroup" => 0, 
                );
            } else {
                $response = array("subgroups" => $subgroups, "currentSubgroup" => 0);
            }
            break;
        case "lesson":
            if($lesson["isLinked"]) {
                // находим связанные группы для группы этого урока
                $linkedSubgroupsLesson = $linkedgroups[$lesson["idsubgroup"]];
//                
                // для каждой связанной группы находим урок из массива
                foreach($subgroups as $subgroup){
                    foreach($linkedSubgroupsLesson as $linkedSubgroup){
                        if($subgroup["id"] == $linkedSubgroup || 
                           $subgroup["id"] == $lesson["idsubgroup"]){
                            
                        } else {
                            unset($subgroups[$subgroup["id"]]);
                        }
                    }
                }
                
                if($subgroupsFromJS) {
                     // убираем те уроки, которые уже есть в расписании (чтобы в комбобоксе они не отображались)
                    foreach($subgroups as $idSubgroup => $subgroup){
                        foreach($subgroupsFromJS as $idsubgroupJS){
                            if($idSubgroup == $idsubgroupJS && $idSubgroup != $lesson["idsubgroup"]){
                                unset($subgroups[$idSubgroup]);
                            }
                        }
                    }
                }
                $response = array("subgroups" => $subgroups, "currentSubgroup" => $lesson["idsubgroup"]);
            } else {
                $response = array("subgroups" => $subgroups, "currentSubgroup" => $lesson["idsubgroup"]);
            }            
            break;
    }
    
} else {
    // у класса нет групп
}

// send response to client
print json_encode($response);
?>
