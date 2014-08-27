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

// GET VALUES FROM GET
if(isset($_POST["c"])) {$idclass = $_POST["c"];}
if(isset($_POST["s"])) {$idsubject = $_POST["s"];}

// DECLARE VARIABLES NEEDED LATER
//

// GET ALL SUBGROUPS OF CLASS BY CONCRETE SUBJECT
$r = $DB_DO->getRow(QSDO::$getMinMaxIDSubgroupInClassBySubject, $idclass, $idsubject);
$minID = $r["min"];
$maxID = $r["max"];
        
// FORM RESPONSE ARRAY
$response = array("minID" => $minID, "maxID" => $maxID);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>