<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0);
$smarty->assign('block_page_sys', "contacts");
$smarty->assign('block2_page_sys', "subscribers");
// $smarty->debugging=true;
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
$ProfileAuth = $UA->getProfile();
$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PageTitle', "Подписчики ".$ProfileAuth->FI());
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
//$smarty->debugging = true;
if(isset($_GET['id'])) {
    $isProfileLoad = $DB->getOne(QS::$q2, $_GET["id"]);
    if($isProfileLoad){
        $profileLoadFromDb = $DB->getRow(QS::$q3, $_GET["id"]);
        $ProfileLoad = new Profile($_GET["id"], $profileLoadFromDb);
        
    } else {
        header("Location: 404.php");
    }
} else {
    $ProfileLoad = $ProfileAuth;
}
$smarty->assign('ProfileLoad', $ProfileLoad);


$subscriptionsFromDB = $DB->getAll(QS::$getSubscriptionsPart, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
foreach ($subscriptionsFromDB as $subscription) {
    $profileSubscriptionFromDb = $DB->getRow(QS::$q3, $subscription["id"]);
    $profileSubscription = new Profile($subscription["id"], $profileSubscriptionFromDb);
    $subscriptionFI = $profileSubscription->FI();
    $avatarPath = $profileSubscription->ProfilePathAvatar();
    $subscriptions[] = array(
                    "id" => $subscription["id"],
                    "FI" => $subscriptionFI,
                    "avaPath" => $avatarPath,
                    "type" => 1
    );
}

/*****************************************************************/
$unmutualSubscribersPartFromDB =  $DB->getAll(QS::$getSubscribersPart, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
$mutualSubscribersPartFromDB =  $DB->getAll(QS::$getMutualSubscribersPart, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);
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
            $resArrayMutual =  $DB->getAll(QS::$getMutualSubscribersPart, $ProfileLoad->ID, $countToLoad);
        }
        foreach ($unmutualSubscribersPartFromDB as $friendInboxRequest) {
            $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
            $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
            $friendFI = $profileFriend->FI();
            $avatarPath = $profileFriend->ProfilePathAvatar();
            $subscribersPart[] = array(
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
            $subscribersPart[] = array(
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
            $subscribersPart[] = array(
                "id" => $friendInboxRequest["id"],
                "FI" => $friendFI,
                "avaPath" => $avatarPath,
                "type" => 2
            );
        }
    }
} else {
    if($countMutualSubscribersPart == 0){
        $subscribersPart = null;
    } else {
        foreach ($mutualSubscribersPartFromDB as $friendInboxRequest) {
            $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
            $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
            $friendFI = $profileFriend->FI();
            $avatarPath = $profileFriend->ProfilePathAvatar();
            $subscribersPart[] = array(
                "id" => $friendInboxRequest["id"],
                "FI" => $friendFI,
                "avaPath" => $avatarPath,
                "type" => 3
            );
        }
    }
}
/*****************************************************************/
$countSubscriptions = $DB->getOne(QS::$getCountSubscriptions, $ProfileLoad->ID);
$countSubscribers = $DB->getOne(QS::$getCountSubscribers, $ProfileLoad->ID);
$allSubscribers = array(
    "countsubscriptions" => $countSubscriptions,
    "countsubscribers" => $countSubscribers,
    "subscriptions" => $subscriptions,
    "subscribers" => $subscribersPart
);

$smarty->assign('allSubscribers', $allSubscribers);
$smarty->assign('SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS', SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 23);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_contactssub.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnion_contact.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/contactssub.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>