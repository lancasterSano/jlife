<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "general");
$smarty->assign('block2_page_sys', "friends");
$smarty->assign('block3_page_sys', "subscribers");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Общее '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

// Друг ли авторизованный пользователь и Владелец загружаемой страницы
$friend = $DB->getOne(QS::$qfriend1, $ProfileAuth->ID, $ProfileLoad->ID);
$smarty->assign('friend', $friend);



# Количество друзей
$countcontact = $ProfileLoad->countcontact;

$idLoad = $ProfileLoad->ID;

# Персональные данные
$birthday = $ProfileLoad->birthday;
$month = setRussianMonth(date("m", strtotime($birthday)), true);
$day = date("j", strtotime($birthday));
$year = date("Y", strtotime($birthday));
$personal = array(
        "birthday" => $day.' '.$month.' '.$year.' г.',
        "country" => $ProfileLoad->country,
        "city" => $ProfileLoad->city,
        "mobile" => $ProfileLoad->mobile,
        "telephone" => $ProfileLoad->telephone
        //"position" => $ProfileLoad->birthday,
    );

# Получить id всех друзей из таблицы spisokcontactgroupuserNNNN по DISTINCT
$idFriends = $DB->getAll(QS::$getIdFriends, $ProfileID);
foreach($idFriends as $key => $valueIdFriends)
{
    # Получаем персональные данные по id друга
        $profileFriend = $DB->getRow(QS::$q3, $valueIdFriends["iduser"]);
    # Создаем объект друга
        $ProfileLoad = new Profile($valueIdFriends["iduser"], $profileFriend);
    # Получаем путь к аватарке
        $pathAvatar = $ProfileLoad->ProfilePathAvatar();
    
    $friend = array(
        "id" => $valueIdFriends["iduser"],
        "pathAvatar" => $pathAvatar
    );
    
    $friends[] = $friend;
}


$smarty->assign('idLoad',$idLoad);
$smarty->assign('countcontact',$countcontact);
$smarty->assign('personal',$personal);
$smarty->assign('friends',$friends);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 41);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_personal.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionPersonal.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/personal.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>