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
if(isset($_POST["s"])) {$searchKey = $_POST["s"];}
if(isset($_POST["id"])) {$startId = intval($_POST["id"]);}


// DECLARE VARIABLES NEEDED LATER
// 

if($searchKey != ""){
    $searchKey = normalize(htmlspecialchars($searchKey))."%";
    if($startId == 0){
        $type = "firstSearchResult";
        $userIDArray = $DB->getCol(QS::$searchUsersIdFromProfile, $searchKey, $searchKey);
        $countSearchResult = $DB->getOne(QS::$searchCountUsersIdFromProfile, $searchKey, $searchKey);
    } else {
        $type = "loadMoreSearchResults";
        $userIDArray = $DB->getCol(QS::$getMoreSearchUsersIdFromProfile, $searchKey, $searchKey, $startId);
        $countSearchResult = $DB->getOne(QS::$searchCountMoreUsersIdFromProfile, $searchKey, $searchKey, $startId);
    }
    if($userIDArray){
        $isUsers = true;
        foreach ($userIDArray as $userID) {
            $userFromDB = $DB->getRow(QS::$q3, $userID);
            $User = new Profile($userID, $userFromDB);
            $userFI = $User->FI();
            $userAvaPath = $User->ProfilePathAvatar();
            $users[] = array(
                "id" => $userID,
                "FI" => $userFI,
                "avaPath" => $userAvaPath
            );
        }
        if($countSearchResult > count($userIDArray))
            $isMoreUsers = true;
        else
            $isMoreUsers = false;
    } else {
        $isUsers = false;
        $isMoreUsers = false;
    }
} else {
    $isUsers = false;
    $isMoreUsers = false;
    if($startId == 0){
        $type = "firstSearchResult";
    } else {
        $type = "loadMoreSearchResults";
    }
}

// FORM RESPONSE ARRAY
$response = array("users" => $users, "isUsers" => $isUsers, "isMoreUsers" => $isMoreUsers, "type" => $type);

// SEND RESPONSE TO CLIENT
print json_encode($response);
?>