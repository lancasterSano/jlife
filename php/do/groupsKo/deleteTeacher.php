<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

// GET VALUES FROM GET
if(isset($_POST["idT"])) {$idteacher = $_POST["idT"];}

// DECLARE VARIABLES NEEDED LATER
 
// MAIN PART
$Teacher = new Teacher($idteacher);
$IDSchoolDO = $Teacher->idSchool;
$r1 = $DB_DO->query(QSDO::$dTeacher, $idteacher);
$r2 = $DB->query(QS::$dRole, $IDSchoolDO, $idteacher, 2);
if($r1 && $r2){
    $isDeleted = true;
} else {
    $isDeleted = false;
}
// FORM RESPONSE ARRAY
$response = array(
    "isdeleted" => $isDeleted);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>