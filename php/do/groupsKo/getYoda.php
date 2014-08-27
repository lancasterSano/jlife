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
if(isset($_POST["y"])) {$idYoda = $_POST["y"];}

// GET INFO ABOUT YODA
if($idYoda != "0") { 
    $Yoda = new Yoda($idYoda);
    $FIOYoda = $Yoda->FIO();
    $FIOInitialsYoda = $Yoda->FIOInitials();
} else {
    $FIOYoda = "Не назначен";
    $FIOInitialsYoda = "Не назначен";
}

// FORM RESPONSE ARRAY
$response = array("idYoda" => $idYoda, "FIOYoda" => $FIOYoda, "FIOInitialsYoda" => $FIOInitialsYoda);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>