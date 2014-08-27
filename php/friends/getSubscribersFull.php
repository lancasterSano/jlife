<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['t']))   { $type =  intval($_POST['t']);}
if (isset ($_POST['l']))   { $idLoad =  intval($_POST['l']);}

if($type == 1){
    $subscriptionsFullFromDB =  $DB->getAll(QS::$getSubscriptionsFull, $idLoad);
    foreach ($subscriptionsFullFromDB as $subscriptionFullFromDB) {
        $profileFriendFromDb = $DB->getRow(QS::$q3, $subscriptionFullFromDB["id"]);
        $profileFriend = new Profile($subscriptionFullFromDB["id"], $profileFriendFromDb);
        $friendFI = $profileFriend->FI();
        $avatarPath = $profileFriend->ProfilePathAvatar();
        $subscribers[] = array(
            "id" => $subscriptionFullFromDB["id"],
            "FI" => $friendFI,
            "avaPath" => $avatarPath,
            "type" => 1
        );
    }
} else if($type == 2){
    $subscribersFullFromDB = $DB->getAll(QS::$getSubscribersFull, $idLoad);
    $mutualSubscribersFullFromDB = $DB->getAll(QS::$getMutualSubscribersFull, $idLoad);
    if($subscribersFullFromDB){
        foreach ($subscribersFullFromDB as $subscriberFullFromDB) {
            $profileFriendFromDb = $DB->getRow(QS::$q3, $subscriberFullFromDB["id"]);
            $profileFriend = new Profile($subscriberFullFromDB["id"], $profileFriendFromDb);
            $friendFI = $profileFriend->FI();
            $avatarPath = $profileFriend->ProfilePathAvatar();
            $subscribers[] = array(
                "id" => $subscriberFullFromDB["id"],
                "FI" => $friendFI,
                "avaPath" => $avatarPath,
                "type" => 2
            );
        }
    }
    if($mutualSubscribersFullFromDB){
        foreach ($mutualSubscribersFullFromDB as $mutualSubscriberFullFromDB) {
            $profileFriendFromDb = $DB->getRow(QS::$q3, $mutualSubscriberFullFromDB["id"]);
            $profileFriend = new Profile($mutualSubscriberFullFromDB["id"], $profileFriendFromDb);
            $friendFI = $profileFriend->FI();
            $avatarPath = $profileFriend->ProfilePathAvatar();
            $subscribers[] = array(
                "id" => $mutualSubscriberFullFromDB["id"],
                "FI" => $friendFI,
                "avaPath" => $avatarPath,
                "type" => 3
            );
        }
    }
    if(!$subscribersFullFromDB && !$mutualSubscribersFullFromDB) {
        $subscribers = null;
    }
}
print json_encode($subscribers);
?>
