<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['t']))   { $type =  intval($_POST['t']);}

if($type == 1){
    $outboxRequestsPartFromDB = $DB->getAll(QS::$getOutboxRequestsFull, $ProfileAuth->ID);
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
    $inboxNewRequestsFromDB = $DB->getAll(QS::$getInboxRequestsNewFull, $ProfileAuth->ID, $ProfileAuth->ID);
    $inboxOldRequestsFromDB = $DB->getAll(QS::$getInboxRequestsOldFull, $ProfileAuth->ID, $ProfileAuth->ID);
    if($inboxNewRequestsFromDB){
        foreach ($inboxNewRequestsFromDB as $friendInboxRequest) {
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
    if($inboxOldRequestsFromDB){
        foreach ($inboxOldRequestsFromDB as $friendInboxRequest) {
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
    if(!$inboxOldRequestsFromDB && !$inboxNewRequestsFromDB) {
        $requests = null;
    }
}
print json_encode($requests);
?>
