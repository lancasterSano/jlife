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

// получим значения из POST
if(isset($_POST["c"])){$idClass = $_POST["c"];}
if(isset($_POST["dn"])){$dayNumber = $_POST["dn"];}
if(isset($_POST["isT"])){$isTimetable = intval($_POST["isT"]);}
if(isset($_POST["t"])){$lessons = json_decode(stripcslashes($_POST["t"]), true);}

$classFromDB = $DB_DO->getRow(QSDO::$getClass, $idClass);
$idSchool = $classFromDB["schoolS_id"];

$defaultTimeStart = "09:00:00";
$defaultTimeFinish = "09:50:00";

// удалить уроки у этого класса в этот день
$r = $DB_DO->query(QSDO::$deleteTimetableClassDay, $idClass, $dayNumber);

// записать в БД новые уроки
if($isTimetable){
    foreach ($lessons as $lesson) {
        if($lesson["idclassroom"] == 0){
            $classroom = NULL;
        } else {
            $classroom = $lesson["idclassroom"];
        }
        $r = $DB_DO->query(QSDO::$insertTimetable, 
                $lesson["number"],
                $dayNumber,
                $defaultTimeStart,
                $defaultTimeFinish,
                $lesson["idsubgroup"],
                $classroom,
                $idSchool,
                $idClass
        );
    }
}
// сформировать результирующий массив
$response = array();

// send response to client
print json_encode($response);
?>
