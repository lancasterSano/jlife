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

// GET VALUES FROM POST
if(isset($_POST["c"])){$idclass = $_POST["c"];}
if(isset($_POST["s"])){$idschool = $_POST["s"];}
if(isset($_POST["nt"])){$idnewteacher = $_POST["nt"];}

// GET ID CURRENT YODA OF CLASS
$idCurrentYodaFromDB = $DB_DO->getRow(QSDO::$getClassYoda, $idclass);
$idCurrentYoda = $idCurrentYodaFromDB["yodaS_id"];

if($idCurrentYoda){
    if($idnewteacher == "-1"){
        // снятие классного руководителя
        $r = $DB_DO->query(QSDO::$setYodaClass, $idclass, $idCurrentYoda, NULL);
        $response = array("status" => "unsetYoda", "idnewyoda" => "-1");
    } else {
        // получаем idYoda по idSocial нового учителя
        $newTeacher = new Teacher($idnewteacher);
        $r = $DB_DO->getRow(QSDO::$getYodaByIdSocial, $newTeacher->idProfile);
        if(!$r){
            $r = $DB_DO->query(QSDO::$createYoda, $idschool, $newTeacher->idProfile, 
                    $newTeacher->FirstName, $newTeacher->LastName, $newTeacher->MiddleName);
            $idNewYodaFromDb = $DB_DO->getRow("SELECT LAST_INSERT_ID() as id;");
            $idNewYoda = $idNewYodaFromDb["id"];
        } else {
            $idNewYoda = $r["id"];
        }
        $r = $DB_DO->query(QSDO::$setYodaClass, $idclass, $idCurrentYoda, $idNewYoda);
        $response = array("status" => "changeYoda", "idnewyoda" => $idNewYoda, "idprofile" => $newTeacher->idProfile, "fioInitials" => $newTeacher->FIOInitials());
    }
} else {
    if($idnewteacher == "-1"){
        $response = array("status" => "nothing", "idnewyoda" => "-1");
    } else {
         // получаем idYoda по idSocial нового учителя
        $newTeacher = new Teacher($idnewteacher);
        $r = $DB_DO->getRow(QSDO::$getYodaByIdSocial, $newTeacher->idProfile);
        if(!$r){
            $r = $DB_DO->query(QSDO::$createYoda, $idschool, $newTeacher->idProfile, 
                    $newTeacher->FirstName, $newTeacher->LastName, $newTeacher->MiddleName);
            $idNewYodaFromDb = $DB_DO->getRow("SELECT LAST_INSERT_ID() as id;");
            $idNewYoda = $idNewYodaFromDb["id"];
        } else {
            $idNewYoda = $r["id"];
        }
        $r = $DB_DO->query(QSDO::$setYodaClass, $idclass, NULL, $idNewYoda);
        $response = array("status" => "setYoda", "idnewyoda" => $idNewYoda, "idprofile" => $newTeacher->idProfile, "fioInitials" => $newTeacher->FIOInitials());
    }
}

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>