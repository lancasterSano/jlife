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
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "photo");
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

$smarty->assign('PageTitle', 'Фото'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);


$comment = array(
        "idComment" => "2",
        "textComment" => "В Дагестане много мокасинов",
        "authorCommentID" => "12",
        "authorCommentFIO" => "Адольф Габриэлович Гитлер",
        "authorCommentPathAvatar" => '../img/album.jpg',
        "dateComment" => "13,17,2259",
        "countLikeComment" => "23",
        "isProfileAuthSetLike" => "",
        "answerCommentId" => "1",
        "answerAuthorCommentFIO" => "Еся Виссарионович",
        //"extension" => $valueC["extension"],
        //"subCommnt" => array()
    );
$smarty->assign('comment',$comment);

    $photo = array(
        "idPhoto" => '15',  // id
        "photoDescAlbum" => 'Тут все самое лучшее',  // id
        "photoNum" => '13',  // id
        "photoOwner" => 'Анна Шустер',  // id
        "photoCol" => '25',  // id
        "photoDesc" => 'Как узнали? В первом случае мы заподозрили, что с чуваком что-то не то, а мы знали о нём достаточно, чтобы сделать несколько правильных звонков, и узнать, что его забирали с работы в ФБР.',  // id
        "photoDate" => '25,56,2056',  // id
		"countComent" =>'20' ,  //  
        "photoPath" => '../img/photo.jpg',  //  photopath
        "countLikePhoto" =>'50', //   countlike
        "isProfileAuthSetLike" =>'',
    );

$smarty->assign('photo',$photo);



/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/USMF/inc_photo.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionAlbumsSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/photo.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>