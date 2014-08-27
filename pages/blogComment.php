<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "blogComment");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);

/***** SIDEBAR *****/

if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
$p1 = $DB->getRow(QS::$q2, $ProfileID);
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Коментарии к статьям '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

$authorLoad = $ProfileLoad->FIO();
$idAuthorLoad = $ProfileLoad->ID;

$authorAuth = $ProfileAuth->FIO();
$idAuthorAuth = $ProfileAuth->ID;

// Делаем запрос на все комментарии
$allComments = $DB->getAll(QS::$getComments, $idAuthorLoad, $idAuthorLoad, SETTING_COUNT_FIRST_LOAD_ENTITY, $idAuthorLoad, 
                                            SETTING_COUNT_FIRST_LOAD_ENTITY);
//print_r($allComments);
foreach($allComments as $key => $value)
{
    // Делаем запрос на получение полной информации об авторе комментария
    $authorInfo = $DB->getRow(QS::$q3, $value["profile_id"]);
    // Создаем новый объект автора комментария
    $authorComment = new Profile($value["profile_id"], $authorInfo);

    // Делаем запрос на получение названия статьи, которой адресован комментарий
    $postName = $DB->getRow(QS::$getPostNameInComment, $idAuthorLoad, $idAuthorLoad, $idAuthorLoad, $idAuthorLoad, $value["blog".$idAuthorLoad."_id"]);

    // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
    $like_com = $DB->getOne(QS::$getLikeCommentBlog, $idAuthorLoad, $idAuthorLoad, $idAuthorAuth, $idAuthorLoad, $value["id"]);

    // id комента на который отвечают
    $answerCommentID = $value["commentblog".$ProfileLoad->ID."_id"];
    //print_r($answerCommentID);
    if($answerCommentID != null)
            {  
                // Делаем запрос на получение id автора, которому адресован комментарий
                $answerProfile = $DB->getRow(QS::$getProfileId, $ProfileLoad->ID, $answerCommentID);
                // Получаем id автора
                $answerProfileID = $answerProfile["profile_id"];
                //print_r($answerProfileID);
                // Делаем запрос на получение информации об авторе, которому адресован комментарий
                $author_comment_special = $DB->getRow(QS::$getProfileFI, $answerProfileID);
                // Получаем Имя и Фамилию автора, к которому адресован комментарий
                $answerCommentFIO = $author_comment_special["firstname"]." ".$author_comment_special["lastname"];
                // // полное обращение
                // preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueC["text"], $matches);
                // // Обращение безя запятой
                // if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
                // // текст без обращения и запятой
                // $valueC["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueC["text"]);
            }
$comment = array(
        "id" => $value["id"],
        "text" => $value["text"],
        "authorCommentID" => $value["profile_id"],
        "authorCommentFIO" => $authorComment->FI(),
        "authorCommentPathAvatar" => $authorComment->ProfilePathAvatar(),
        "datetime" => $value["datetime"],
        "countLikeComment" => $value["countlike"],
        "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
        "answerCommentId" => $answerCommentID,
        "answerAuthorCommentFIO" => $answerCommentFIO,
        "extension" => $value["extension"],
        "postName" => $postName["name"],
        //"subCommnt" => array()
    );

    if($comment["extension"] == null)
            {                                              
                $comments[$value["id"]]=$comment;
                $root_comment = $comment;
                $last_add_comment = $comment;           
            }
    else if($comment["extension"] != null)
            {
                $comments[$root_comment["id"]]["text"] .= $comment["text"];
            }
}

$smarty->assign('comments',$comments);  

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 22);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_blogComment.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionBlogCommentSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/blogComment.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>