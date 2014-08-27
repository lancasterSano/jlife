<?php 
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

$smarty->assign('PageTitle', 'Коментарии блога'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);


$comment = array(
        "idComment" => "1",
        "textComment" => "В Дагестане много мокасинов",
        "authorCommentID" => "1",
        "authorCommentFIO" => "Адольф Габриэлович Гитлер",
        "authorCommentPathAvatar" => '../img/album.jpg',
        "dateComment" => "13,17,2259",
        "countLikeComment" => "23",
        "isProfileAuthSetLike" => "",
        "answerCommentId" => "1",
        "answerAuthorCommentFIO" => "Еся Виссарионович",
        "extension" => "",
        "postName" => "5 мифов о квантовом интернете",
        //"subCommnt" => array()
    );
$smarty->assign('comment',$comment);  

//$smarty->debugging = true;

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 22);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_blogComment.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionBlogCommentSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/blogComment.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>