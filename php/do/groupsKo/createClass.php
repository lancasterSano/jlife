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
// GET VALUES FROM GET
if(isset($_POST["s"])) {$idSchool = $_POST["s"];}
if(isset($_POST["let"])) {$letter = $_POST["let"];}
if(isset($_POST["lvl"])) {$level = $_POST["lvl"];}
if(isset($_POST["t"])) {$idTeacher = $_POST["t"];}

// DECLARE VARIABLES NEEDED LATER
$response = null;
$Teacher = new Teacher($idTeacher);

// ADD CLASS TO DB
$r = $DB_DO->query(QSDO::$createClass, $level, date("Y"), $idSchool, $letter);
if($r) {
    $idClass = $DB_DO->getOne("SELECT LAST_INSERT_ID()");
//    echo $idClass;
    
    if($idTeacher) {
        // CREATE YODA
        $isYoda = $DB_DO->query(QSDO::$createYoda, $idSchool, $Teacher->idProfile, $Teacher->FirstName, $Teacher->LastName, $Teacher->MiddleName);
        if($isYoda){
            $idYoda = $DB_DO->getOne("SELECT LAST_INSERT_ID()");
//            echo $idYoda;

            // SET CLASS TO YODA
            $isYodaSet = $DB_DO->query(QSDO::$setClassToYoda, $idClass, $idYoda);
            if($isYodaSet) {
                $response = array("status" => "addclasswithyoda", "idYodaProfile" => $Teacher->idProfile, "FIOYoda" => $Teacher->FIOInitials(), "idYoda" => $idYoda, "idClass" => $idClass);
            } else {
                $response = array("status" => "failsetyodaclass");
            }
        } else {
            $response = array("status" => "failaddyoda");
        }
    } else {
        $response = array("status" => "addclasswithoutyoda", "idClass" => $idClass, "idYoda" => NULL);
    }
} else {
    $response = array("status" => "failaddclass");
}

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
