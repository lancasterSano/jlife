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
if(isset($_POST["c"])) {$idClass = $_POST["c"];}

// GET CURRENT SCHOOL BY IDCLASS
$idSchool = $DB_DO->getOne("SELECT `schoolS_id` FROM `classs` WHERE id = ?i;", $idClass);

// GET SUBJECTS OF CURRENT CLASS
$subjects = $DB_DO->getAll(QSDO::$getAllSubjectsOfClass, $idClass, $idSchool);
$subgroups = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idClass);

// FORM RESPONSE ARRAY
$response = array("subjects" => $subjects, "subgroups" => $subgroups);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
