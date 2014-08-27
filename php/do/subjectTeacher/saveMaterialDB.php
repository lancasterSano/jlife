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
$name = $_POST["name"];
$idT = $_POST["idT"];
$l = $_POST["l"];
$c = $_POST["c"];
$idSubject = $DB_DO->getOne(QSDO::$getSubjectByNameAndLevelAndComplexity, $name , $l, $c);
$countsection = $DB_DO->getOne(QSDO::$getCountSection, $idSubject);
$r = $DB_DO->query(QSDO::$createMaterial, $idT, $idSubject);
$idFromDB = $DB_DO->getRow("SELECT LAST_INSERT_ID() as id");
$id = $idFromDB["id"];
$date = setRussianDate(date("j n Y", strtotime("now")));

$response = array("countsection" => $countsection, "id" => $id, "date" => $date);
print json_encode($response);
##############################################
?>
