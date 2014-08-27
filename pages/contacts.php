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

$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PageTitle', "Контакты ".$ProfileAuth->FI());
$smarty->assign('PROJECT_PATH', PROJECT_PATH);


if(isset($_GET['id'])) {
    $isProfileLoad = $DB->getOne(QS::$q2, $_GET["id"]);
    if($isProfileLoad){
        $idLoad = $_GET["id"];
        $profileLoadFromDb = $DB->getRow(QS::$q3, $idLoad);
        $ProfileLoad = new Profile($idLoad, $profileLoadFromDb);
        
    } else {
        header("Location: 404.php");
    }
} else {
    $ProfileLoad = $ProfileAuth;
    $idLoad = $idAuth;
}
if($idAuth == $idLoad){
    $groupsFromDb = $DB->getAll(QS::$qfriend3, $idAuth); //получить все группы пользователя (id, name, countuser)
    foreach ($groupsFromDb as $group) {
        $idGroup = $group["id"];
        $nameGroup = $group["name"];
        $countFriendsInGroup = $group["countuser"];
        $friendsFromDb = $DB->getAll(QS::$qfriend4, $idAuth, $idAuth, $idGroup, SETTING_COUNT_FIRST_LOAD_FRIENDS); // select friends by ID Group
        foreach($friendsFromDb as $friend) {
            $idFriend = $friend["id"];
            $deleted = $friend["deleted"];
            $friendFromDb = $DB->getRow(QS::$q3, $idFriend);
            $profileFriend = new Profile($idFriend, $friendFromDb);
            $fiFriend = $profileFriend->FI();
            $avaPathFriend = $profileFriend->ProfilePathAvatar();
            
            $friendsInGroup[] = array(
                "id" => $idFriend,
                "FI" => $fiFriend,
                "avaPath" => $avaPathFriend,
                "deleted" => $deleted                     
            );
        }
        $groups[] = array(
        "id" => $idGroup,
        "name" => $nameGroup,
        "countfriends" => $countFriendsInGroup,
        "friends" => $friendsInGroup
        );
        unset($friendsInGroup);
    }
} else {
    $friendsFromDb = $DB->getAll(QS::$getAllFriends, $idLoad);
    if($friendsFromDb){
        foreach($friendsFromDb as $friend) {
            $idFriend = $friend["id"];
            $deleted = $friend["deleted"];
            $friendFromDb = $DB->getRow(QS::$q3, $idFriend);
            $profileFriend = new Profile($idFriend, $friendFromDb);
            $fiFriend = $profileFriend->FI();
            $avaPathFriend = $profileFriend->ProfilePathAvatar();

            $friends[] = array(
                "id" => $idFriend,
                "FI" => $fiFriend,
                "avaPath" => $avaPathFriend,
                "deleted" => $deleted                     
            );
        }
        $countfriends = count($friends);
    } else {
        $countfriends = 0;
        $friends = null;
    }
    $groups[] = array(
        "id" => 0,
        "name" => "Все контакты",
        "countfriends" => $countfriends,
        "friends" => $friends
    );
}

$smarty->assign('showmode', 'part');
$smarty->assign('locationmode', 'std');
//группы владельца страницы

$smarty->assign('ProfileLoad', $ProfileLoad);

$smarty->assign('groups', $groups);
$smarty->assign('friends', $friends);
$smarty->assign('SETTING_COUNT_FIRST_LOAD_FRIENDS', SETTING_COUNT_FIRST_LOAD_FRIENDS);


/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 21);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_contacts.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnion_contact.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/contacts.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>