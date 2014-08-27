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

// DECLARE VARIABLES NEEDED LATER
$result_array = array();

// GET TEACHERS FROM DB
$r = $DB_DO->getAll(QSDO::$getAllTeachersIDs, $idSchool);
if($r) {
    foreach ($r as $v) {
        $Teacher = new Teacher($v["id"]);
        $result_array[$v["id"]]["id"] = $Teacher->idTeacher;
        $result_array[$v["id"]]["FI"] = $Teacher->FIOInitials();
        unset($Teacher);
    }
    $response = $result_array;
} else {
    $response = null;
}
print json_encode($response);
##############################################
?>
