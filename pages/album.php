<?php 
// require_once '.htpaths';
// require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
// require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
// require_once(PROJECT_PATH."/php/common.php");
// require_once(PROJECT_PATH."/php/class/QueryStorage.php");
// require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
// require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
// require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');


// /**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

// $ProfileLoad = $UA->getProfile();
// $smarty->assign('PROJECT_PATH', PROJECT_PATH);
// $smarty->assign('is_do', 0); 
// require_once(PROJECT_PATH."/pages/development.php");

# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php");
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "album");
$smarty->assign('block2_page_sys', "friends");
$smarty->assign('block3_page_sys', "subscribers");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);

$debug_context[SETTING_debug]['SERVER URI:'] = $_SERVER['REQUEST_URI'];



if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
// $p1 = $DB->getRow(QS::$q2, $ProfileID);
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Название альбома '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

// Друг ли авторизованный пользователь и Владелец загружаемой страницы
$friend = $DB->getOne(QS::$qfriend1, $ProfileAuth->ID, $ProfileLoad->ID);
$smarty->assign('friend', $friend);

if(isset($_GET['album'])) $idAlbum = $_GET['album'];

$allPhotosChoosenAlbum = $DB->getAll(QS::$getAlbumsPhotos, $ProfileID, $ProfileID, $idAlbum);
$smarty->assign('allPhotosChoosenAlbum',$allPhotosChoosenAlbum);

$photoAlbom = array(
        "idPhotoAlbom" => '15',  // id
        "namePhotoAlbom" => 'Вот так вот',  // name
		"countPhoto" =>'20' ,  //  
        "countLikePhotoAlbom" =>'50', //   countlike
        "isProfileAuthSetLike" =>'',
    );
$smarty->assign('photoAlbom',$photoAlbom);

    $photo = array(
        "idPhoto" => '15',  // id
		"countComent" =>'20' ,  //  
        "photoPath" => '../img/34.jpg',  //  photopath
        "countLikePhoto" =>'50', //   countlike
        "isProfileAuthSetLike" =>'',
        
    );

$smarty->assign('photo',$photo);






/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 51);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_album.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionAlbumSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/album.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>