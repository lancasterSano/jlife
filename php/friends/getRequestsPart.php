<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['t']))   { $type =  intval($_POST['t']);}

if($type == 1){
    $outboxRequestsPartFromDB =  $DB->getAll(QS::$getOutboxRequestsPart, $ProfileAuth->ID, SETTING_COUNT_FIRST_LOAD_REQUESTS);
    foreach ($outboxRequestsPartFromDB as $friendOutboxRequest) {
        $profileFriendFromDb = $DB->getRow(QS::$q3, $friendOutboxRequest["id"]);
        $profileFriend = new Profile($friendOutboxRequest["id"], $profileFriendFromDb);
        $friendFI = $profileFriend->FI();
        $avatarPath = $profileFriend->ProfilePathAvatar();
        $requests[] = array(
            "id" => $friendOutboxRequest["id"],
            "FI" => $friendFI,
            "avaPath" => $avatarPath,
            "type" => 1
        );
    }
} else if($type == 2){
    $inboxNewRequestsPartFromDB =  $DB->getAll(QS::$getInboxRequestsNewPart, $ProfileAuth->ID, $ProfileAuth->ID, SETTING_COUNT_FIRST_LOAD_REQUESTS);
    $inboxOldRequestPartFromDB =  $DB->getAll(QS::$getInboxRequestsOldPart, $ProfileAuth->ID, $ProfileAuth->ID, SETTING_COUNT_FIRST_LOAD_REQUESTS);
    if($inboxOldRequestPartFromDB){
        $countOldInboxRequestsPart = count($inboxOldRequestPartFromDB);
    } else {
        $countOldInboxRequestsPart = 0;
    }
    if($inboxNewRequestsPartFromDB){
        $countNewInboxRequestsPart = count($inboxNewRequestsPartFromDB);
    } else {
        $countNewInboxRequestsPart = 0;
    }
    if($countNewInboxRequestsPart != 0){
        // new requests > 0 
        if($countNewInboxRequestsPart < SETTING_COUNT_FIRST_LOAD_REQUESTS){
            //countnew < setting
            if($countOldInboxRequestsPart == 0){
                $resArrayOld = $inboxOldRequestPartFromDB;
            } else {
                $countToLoad = SETTING_COUNT_FIRST_LOAD_REQUESTS - $countNewInboxRequestsPart;
                $resArrayOld =  $DB->getAll(QS::$getInboxRequestsOldPart, $ProfileAuth->ID, $ProfileAuth->ID, $countToLoad);
            }
            foreach ($inboxNewRequestsPartFromDB as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $requests[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 2
                );
            }
            foreach ($resArrayOld as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $requests[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 3
                );
            }
        } else {
            foreach ($inboxNewRequestsPartFromDB as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $requests[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 2
                );
            }
        }
    } else {
        if($countOldInboxRequestsPart == 0){
            $requests = null;
        } else {
            foreach ($inboxOldRequestPartFromDB as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $requests[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 3
                );
            }
        }
    }
}
print json_encode($requests);
?>
