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

$classFromDB = $DB_DO->getRow(QSDO::$getClass, $idClass);
$idschool = $classFromDB["schoolS_id"];

$classroomsFromDB = $DB_DO->getAll(QSDO::$getAllCabinetsOfSchool, $idschool);

// сформировать результирующий массив
$response = $classroomsFromDB;

// send response to client
print json_encode($response);
?>
