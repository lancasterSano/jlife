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
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "comment");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'коментарии блога'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

$Album = array(
        "idPhotoAlbom" => '15',  // id
        "namePhotoAlbom" => 'Вот так вот',  // name
		"countPhoto" =>'20' ,  //  
        "countLikePhotoAlbom" =>'50', //   countlike
        "isProfileAuthSetLike" =>'',
    );
    
    $smarty->assign('Album', $Album);
    
    $comment = array(
        "idComment" => "1",
        "idCommentAlbum" => "1",
        "photoPath" => "../img/album.jpg",
        "textComment" => "ного мокасинов",
        "textNameAlbum" => "В Дагестане много мокасинов",
        "authorCommentID" => "1",
        "authorCommentFIO" => "Адольф Габриэлович Гитлер",
        "authorCommentPathAvatar" => '../img/album1.jpg',
        "dateComment" => "13,17,2259",
        "countLikeComment" => "23",
        "isProfileAuthSetLike" => "",
        "answerCommentId" => "1",
        "answerAuthorCommentFIO" => "Еся Виссарионович",
        "extension" => "",
        //"subCommnt" => array()
    );

$smarty->assign('comment', $comment);
//$smarty->debugging = true;



/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 52);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_photoAlbumComment.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionPhotoAlbumCommentSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/photoAlbumComment.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>