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
$idL = $_POST["idl"];
$idS = $_POST["ids"];
$idM = $_POST["idm"];
$ddate = $_POST["d"]."%";

$r = $DB_DO->getAll(QSDO::$getParagraphsTestsByDayToTeacher, $idL, $idS, $idM, $ddate);
foreach ($r as $v) {
    $paragraph = $DB_DO->getRow(QSDO::$getParagraph, $v["paragraphS_id1"]);
    $response[] = array(
        "mark" => $v["mark"],
        "name" => $paragraph["name"],
        "number" => $paragraph["number"],
        "id" => $paragraph["id"]
    );
}
print json_encode($response);
?>