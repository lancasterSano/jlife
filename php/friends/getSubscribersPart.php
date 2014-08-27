<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['t']))   { $type =  intval($_POST['t']);}
if (isset ($_POST['l']))   { $idLoad =  intval($_POST['l']);}

if($type == 1){
    $subscriptionsPartFromDB =  $DB->getAll(QS::$getSubscriptionsPart, $idLoad, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
    foreach ($subscriptionsPartFromDB as $subscriptionPartFromDB) {
        $profileFriendFromDb = $DB->getRow(QS::$q3, $subscriptionPartFromDB["id"]);
        $profileFriend = new Profile($subscriptionPartFromDB["id"], $profileFriendFromDb);
        $friendFI = $profileFriend->FI();
        $avatarPath = $profileFriend->ProfilePathAvatar();
        $subscribers[] = array(
            "id" => $subscriptionPartFromDB["id"],
            "FI" => $friendFI,
            "avaPath" => $avatarPath,
            "type" => 1
        );
    }
} else if($type == 2){
    $unmutualSubscribersPartFromDB =  $DB->getAll(QS::$getSubscribersPart, $idLoad, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
    $mutualSubscribersPartFromDB =  $DB->getAll(QS::$getMutualSubscribersPart, $idLoad, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
    if($mutualSubscribersPartFromDB){
        $countMutualSubscribersPart = count($mutualSubscribersPartFromDB);
    } else {
        $countMutualSubscribersPart = 0;
    }
    if($unmutualSubscribersPartFromDB){
        $countUnmutualSubscribersPart = count($unmutualSubscribersPartFromDB);
    } else {
        $countUnmutualSubscribersPart = 0;
    }
    if($countUnmutualSubscribersPart != 0){
        // new requests > 0 
        if($countUnmutualSubscribersPart < SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS){
            //countnew < setting
            if($countMutualSubscribersPart == 0){
                $resArrayMutual = $mutualSubscribersPartFromDB;
            } else {
                $countToLoad = SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS - $countUnmutualSubscribersPart;
                $resArrayMutual =  $DB->getAll(QS::$getMutualSubscribersPart, $idLoad, $countToLoad);
            }
            foreach ($unmutualSubscribersPartFromDB as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $subscribers[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 2
                );
            }
            foreach ($resArrayMutual as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $subscribers[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 3
                );
            }
        } else {
            foreach ($unmutualSubscribersPartFromDB as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $subscribers[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 2
                );
            }
        }
    } else {
        if($countMutualSubscribersPart == 0){
            $subscribers = null;
        } else {
            foreach ($mutualSubscribersPartFromDB as $friendInboxRequest) {
                $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
                $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
                $friendFI = $profileFriend->FI();
                $avatarPath = $profileFriend->ProfilePathAvatar();
                $subscribers[] = array(
                    "id" => $friendInboxRequest["id"],
                    "FI" => $friendFI,
                    "avaPath" => $avatarPath,
                    "type" => 3
                );
            }
        }
    }
}
print json_encode($subscribers);
?>
