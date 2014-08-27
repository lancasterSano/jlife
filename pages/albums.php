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
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "albums");
// $smarty->assign('block2_page_sys', "friends");
// $smarty->assign('block3_page_sys', "subscribers");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
$p1 = $DB->getRow(QS::$q2, $ProfileID);
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Альбомы '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

// Друг ли авторизованный пользователь и Владелец загружаемой страницы
$friend = $DB->getOne(QS::$qfriend1, $ProfileAuth->ID, $ProfileLoad->ID);
$smarty->assign('friend', $friend);

// Получаем ID владельца страницы для корректной отрисовки кнопки "Удалить"
$idLoad = $ProfileLoad->ID;     
$smarty->assign('idLoad',$idLoad);

// Получить ID авторизированного профиля корректной отрисовки кнопки "Удалить"
$idAuth = $ProfileAuth->ID;
$smarty->assign('idAuth',$idAuth);

$allAlbumsQ = $DB->getAll(QS::$getAllAlbums, $ProfileID, SETTING_COUNT_FIRST_LOAD_ENTITY);
//print_r($p);
foreach($allAlbumsQ as $valueAllAlbums)
{    

    // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
    $like = $DB->getOne(QS::$getLikeAlbum, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID,
                           $valueAllAlbums["id"]);

    $allCommentsQ = $DB->getAll(QS::$getCommentsAlbum, $ProfileID, $ProfileID, $valueAllAlbums["id"],
                                                       $ProfileID, $ProfileID, $valueAllAlbums["id"],
                                                       SETTING_COUNT_FIRST_LOAD_COMMENTS);

    foreach($allCommentsQ as $valueAllComments)
    {   
    // Делаем запрос на получение полной информации об авторе комментария
    $authorInfo = $DB->getRow(QS::$q3, $valueAllComments["profile_id"]);
    // Создаем новый объект автора комментария
    $authorComment = new Profile($valueAllComments["profile_id"], $authorInfo);
    
    // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
    $like_com = $DB->getOne(QS::$getLikeCommentAlbum, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $valueAllComments["id"]);

    // id комента на который отвечают
    $answerCommentID = $valueAllComments["commentalbum".$ProfileLoad->ID."_id"];
    //print_r($answerCommentID);
    if($answerCommentID != null)
    {  
        // Делаем запрос на получение id автора, которому адресован комментарий
        $answerProfile = $DB->getRow(QS::$getProfileId_2, $ProfileLoad->ID, $answerCommentID);
        // Получаем id автора
        $answerProfileID = $answerProfile["profile_id"];
        // Делаем запрос на получение информации об авторе, которому адресован комментарий
        $author_comment_special = $DB->getRow(QS::$getProfileFI_2, $answerProfileID);
        // Получаем Имя и Фамилию автора, к которому адресован комментарий
        $answerCommentFIO = $author_comment_special["lastname"]." ".$author_comment_special["firstname"];
        // полное обращение
        preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueAllComments["text"], $matches);
        // Обращение безя запятой
        if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
        // текст без обращения и запятой
        $valueAllComments["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueAllComments["text"]);
    }

    // print_r($authorComment->ID."_".$idLoad."<br>");
    $dateTimeComment = setRussianDate(date("j n Y H i", strtotime($valueAllComments["datetime"])));
    $comment = array(
        "id" => $valueAllComments["id"],
        //"adress" => $matches_adress[0],
        "datetime" => $dateTimeComment,
        "text" => $valueAllComments["text"],
        "countLike" => $valueAllComments["countlike"],
        "albumNNN_id" => $valueAllComments["album".$ProfileLoad->ID."_id"],
        "commentAlbumNNN_id" => $valueAllComments["commentalbum".$ProfileLoad->ID."_id"],
        "authorCommentId" => $valueAllComments["profile_id"],
        "authorCommentFIO" => $authorComment->FI(),
        "authorCommentPathAvatar" => $authorComment->ProfilePathAvatar(),
        "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
        // "isProfileOwnerComment" => ($authorComment->ID == $idAuth ? true:false),
        "answerCommentId" => $answerCommentID,
        "answerAuthorCommentFIO" => $answerCommentFIO,
        "answerAuthorID" => $answerProfileID,
        "extension" => $valueAllComments["extension"]
    );
    if($comment["extension"] == null)
            {                                              
                $comments[$valueAllComments["id"]]=$comment;
                $root_comment = $comment;
                //$last_add_comment = $comment;
            }
    else if($comment["extension"] != null)
            {
                $comments[$root_comment["id"]]["text"] .= $comment["text"];
            }
    //$comments[$valueAllComments["id"]]=$comment;
    }
    $album = array(
        "id" => $valueAllAlbums["id"],  // id
        "photoPathAlbum" => '../img/album99.jpg',  //  photoPathAlbum
        "name" => $valueAllAlbums["name"],   //  name
        "description" => $valueAllAlbums["description"],  // description
		"countPhoto" => $valueAllAlbums["countphoto"],  // countPhoto 
        "countComment" => $valueAllAlbums["countcomment"],  //  countComment
        "countLike" =>$valueAllAlbums["countlike"], //   countLike
        "isProfileAuthSetLike" =>$like != null ? ($like["count"]>0 ? true:false) : null, // isProfileSetLike
        "comments" => $comments // comments
    );
    $albums[$valueAllAlbums["id"]]=$album;
    unset($comments);
}    
    // print_r($albums);exit();
$smarty->assign('albums',$albums);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 53);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_albums.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionAlbumsSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/albums.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>