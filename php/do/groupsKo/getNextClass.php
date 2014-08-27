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
if(isset($_POST["s"])) {$idSchool = $_POST["s"];}
if(isset($_POST["l"])) {$level = $_POST["l"];}

// DECLARE VARIABLES NEEDED LATER
$nextclass = null;
$respletter = null;
$currentletter = null;
$classes = array("А", "Б", "В", "Г", "Д", "Е", "Ж", "З", "И", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ш", "Щ", "Э", "Ю", "Я");
$startletter = $classes[0];

// GET FIRST LETTER OF NEEDED LEVEL
$minclass = $DB_DO->getOne(QSDO::$getMinimumLetterOfClass, $level, $idSchool);
if(!$minclass) {
    $nextclass = $level."-".$startletter;
    $respletter = $startletter;
} else {
    // ASSIGN TO NEXTCLASS SOME VALUE
    if($minclass != $startletter) {
        $nextclass = $startletter;
    } else {
        for($i = 1; $i < count($classes); $i++) {
            $currentletter = $classes[$i];
            $r = $DB_DO->getRow(QSDO::$checkClassExists, $level, $idSchool, $currentletter);
            if(!$r) {
                $respletter  = $classes[$i];
                $nextclass = $level."-".$classes[$i];
                break;
            }
        }
    }
}
// FORM RESPONSE ARRAY
$response = array("name" => $nextclass, "letter" => $respletter);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>
