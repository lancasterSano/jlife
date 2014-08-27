<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "contacts");
$smarty->assign('block2_page_sys', "friends");

/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PageTitle', "Заявки ".$ProfileAuth->FI());
$smarty->assign('PROJECT_PATH', PROJECT_PATH);

//Список исходящих заявок
$outboxRequestsPartFromDB =  $DB->getAll(QS::$getOutboxRequestsPart, $ProfileAuth->ID, SETTING_COUNT_FIRST_LOAD_REQUESTS);
foreach ($outboxRequestsPartFromDB as $friendOutboxRequest) {
    $profileFriendFromDb = $DB->getRow(QS::$q3, $friendOutboxRequest["id"]);
    $profileFriend = new Profile($friendOutboxRequest["id"], $profileFriendFromDb);
    $friendFI = $profileFriend->FI();
    $avatarPath = $profileFriend->ProfilePathAvatar();
    $outboxRequestsPart[] = array(
        "id" => $friendOutboxRequest["id"],
        "FI" => $friendFI,
        "avaPath" => $avatarPath,
        "type" => 1
    );
}
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
            $inboxRequestsPart[] = array(
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
            $inboxRequestsPart[] = array(
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
            $inboxRequestsPart[] = array(
                "id" => $friendInboxRequest["id"],
                "FI" => $friendFI,
                "avaPath" => $avatarPath,
                "type" => 2
            );
        }
    }
} else {
    if($countOldInboxRequestsPart == 0){
        $inboxRequestsPart = null;
    } else {
        foreach ($inboxOldRequestPartFromDB as $friendInboxRequest) {
            $profileFriendFromDb = $DB->getRow(QS::$q3, $friendInboxRequest["id"]);
            $profileFriend = new Profile($friendInboxRequest["id"], $profileFriendFromDb);
            $friendFI = $profileFriend->FI();
            $avatarPath = $profileFriend->ProfilePathAvatar();
            $inboxRequestsPart[] = array(
                "id" => $friendInboxRequest["id"],
                "FI" => $friendFI,
                "avaPath" => $avatarPath,
                "type" => 3
            );
        }
    }
}

$countOutboxRequests = $DB->getOne(QS::$getCountOutboxRequests, $ProfileAuth->ID);
$countInboxRequests = $DB->getOne(QS::$getCountInboxRequests, $ProfileAuth->ID, $ProfileAuth->ID);
$allRequests["outbox"] = array("requests" => $outboxRequestsPart, "count" => count($outboxRequestsPart));
$allRequests["countoutbox"] = $countOutboxRequests;
$allRequests["inbox"] = array("requests" => $inboxRequestsPart, "count" => count($inboxRequestsPart));
$allRequests["countinbox"] = $countInboxRequests;

$smarty->assign('allRequests', $allRequests);
$smarty->assign('SETTING_COUNT_FIRST_LOAD_REQUESTS', SETTING_COUNT_FIRST_LOAD_REQUESTS);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 22);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_contactsadd.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnion_contact.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/contactsadd.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>