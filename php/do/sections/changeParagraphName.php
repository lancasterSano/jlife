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

// GET VALUES FROM GET
if(isset($_POST["idP"])) {$idParagraph = $_POST["idP"];}
if(isset($_POST["n"])) {$nameParagraph = $_POST["n"];}

// UPDATE NAME PARAGRAPH
$r = $DB_DO->query(QSDO::$changeNameParagraph, normalize(htmlspecialchars($nameParagraph)), $idParagraph);

// SEND RESPONSE
$response = $r;
print json_encode($response);
?>
