<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "notes");
$smarty->assign('block2_page_sys', "friends");
$smarty->assign('block3_page_sys', "subscribers");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);

$owner = (isset($_GET['m']) && $_GET['m']>0) ? true : false;
if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# IS NUMBER ....
# Exist Profile(Profile) by [ID]
// $p1 = $DB->getRow(QS::$q2, $ProfileID);
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	// var_dump($p); echo "[".$_GET['id']." / ".$ProfileID."]"; exit();
	// Z0RT45
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php?erty=34".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);


$smarty->assign('PageTitle', 'Записи '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);
//$smarty->debugging = true;

// Друг ли авторизованный пользователь и Владелец загружаемой страницы
$friend = $DB->getOne(QS::$qfriend1, $ProfileAuth->ID, $ProfileLoad->ID);
$smarty->assign('friend', $friend);
// является ли авторизованный пользователь подписчиком у Владелеца загружаемой страницы
$subscribe = $DB->getOne(QS::$qsubscribe1, $ProfileAuth->ID, $ProfileLoad->ID, $ProfileLoad->ID);
$smarty->assign('subscribe', $subscribe);  

$notes = array();
if($owner) 
	$p = $DB->getAll(QS::$q4_m, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_ENTITY, $ProfileLoad->ID);
else 
	$p = $DB->getAll(QS::$q4, $ProfileLoad->ID, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_ENTITY);

foreach($p as $key => $value)
{   
	// Автор записи
	$authors = $DB->getRow(QS::$q3, $value["idauthor"]);
	$Profile_authors = new Profile($value["idauthor"], $authors);

    $like = null;
	if($value["extension"] == null)
	{
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на записи
        $like = $DB->getOne(QS::$q6, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $value["id"]);
		
		// Все комменты для одной записи       
		$comments = array();
		// $DB->query(QS::$proc1, $ProfileLoad->ID, $value["id"]); 
		// $rez_comments_max = $DB->getOne(QS::$proc_var_max);
		// $rez_comments = $DB->getAll(QS::$q15, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileLoad->ID, $value["id"], $rez_comments_max);

		$rez_comments = $DB->getAll(QS::$q15_1, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileLoad->ID, $value["id"], $ProfileLoad->ID, $ProfileLoad->ID, $value["id"], SETTING_COUNT_FIRST_LOAD_COMMENTS);

		$last_add_note = null;
		foreach($rez_comments as $keyC => $valueC)
		{
			// Автор коммента записи
			$authors_com = $DB->getRow(QS::$q3, $valueC["profile_id"]);
			$Profile_authors_com = new Profile($valueC["profile_id"], $authors_com);
			
			// Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
			$like_com = $DB->getOne(QS::$q8, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $valueC["id"]);
			
			// id комента на который отвечают
			$answerCommentId = $valueC["commentwall".$ProfileLoad->ID."_id"];
			// Сам коммент на который отвечают (взят из списка выводимых уже комментов в UI)
			//$answerCommentFIO = $comments[$answerCommentId]["authorCommentFIO"];
			// если его нет - выгружаю из БД
			if($answerCommentId != null)
			{  
				$answerProfile = $DB->getRow(QS::$q18, $ProfileLoad->ID, $answerCommentId);
                $answerProfileID = $answerProfile["profile_id"];
				$author_comment_special = $DB->getRow(QS::$q5, $answerProfileID);
				$answerCommentFIO = $author_comment_special["firstname"]." ".$author_comment_special["lastname"];
                // полное обращение
                preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueC["text"], $matches);
                // Обращение безя запятой
                if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
                // текст без обращения и запятой
                $valueC["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueC["text"]);
			}

			// var_dump($Profile_authors_com);
            $datetimecomment = setRussianDate(date("j n Y H i", strtotime($valueC["datetime"])));
			$comment = array(
				"idComment" => $valueC["id"],
                "adress" => $matches_adress[0],
				"textComment" => $valueC["text"],
				"authorCommentID" => $valueC["profile_id"],
				"authorCommentFIO" => $Profile_authors_com->FI(),
				"authorCommentPathAvatar" => $Profile_authors_com->ProfilePathAvatar(),
				"dateComment" => $datetimecomment,
				"countLikeComment" => $valueC["countlike"],
				"isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
				"answerCommentId" => $answerCommentId,
				"answerAuthorCommentFIO" => $answerCommentFIO,
                "answerAuthorID" => $answerProfileID,
				"extension" => $valueC["extension"],
				"subCommnt" => array()
			);
				
			if($comment["extension"] == null)
			{                                              
					$comments[$valueC["id"]] = $comment;
					$last_root_add_comment = $comment;      
			}
			else if($comment["extension"] != null)
			{
				$comments[$last_root_add_comment["idComment"]]["textComment"] .= $comment["textComment"];
				$comments[$last_root_add_comment["idComment"]]["authorCommentID"] .= $comment["authorCommentID"];
			}
        }		
	}
	//var_dump($value["idauthsor"] ); exit();
    $datetimenote = setRussianDate(date("j n Y H i", strtotime($value["datetime"])));
    $note = array(
        "idNote" => $value["id"],
        "textNote" => $value["text"],
        "authorNoteID" => $value["idauthor"],
        "authorNoteFIO" => $Profile_authors->FI(),
        "authorNotePathAvatar" => $Profile_authors->ProfilePathAvatar(),
        "dateNote" => $datetimenote,
        "countLikeNote" => $value["countlike"],
        "countComments" => $value["countcomment"],
        "isProfileAuthSetLike" => $like != null ? ($like["count"]>0 ? true:false) : null,
        "extension" => $value["extension"],
        "comments" => $comments
    );
    
    if($last_add_note == null || $last_add_note["extension"] == null)
    {
        // а это уже новое
        $notes[$value["id"]] = $note;
        $last_root_add_note = $note;
        $last_add_note = $note;
    }
    else if($last_add_note["extension"] != null)
    {
        // нужно дописать
        $notes[$last_root_add_note["idNote"]]["textNote"] .= $note["textNote"];
		if($note["extension"] == null)
        {
            $notes[$last_root_add_note["idNote"]]["idNote"] = $note["idNote"];
            $notes[$last_root_add_note["idNote"]]["authorNoteID"] = $note["authorNoteID"];
            $notes[$last_root_add_note["idNote"]]["authorNoteFIO"] = $note["authorNoteFIO"];
            $notes[$last_root_add_note["idNote"]]["authorNotePathAvatar"] = $note["authorNotePathAvatar"];
            $notes[$last_root_add_note["idNote"]]["countLikeNote"] = $note["countLikeNote"];
            $notes[$last_root_add_note["idNote"]]["countComments"] = $note["countComments"];
            $notes[$last_root_add_note["idNote"]]["isProfileAuthSetLike"] = $note["isProfileAuthSetLike"];
            $notes[$last_root_add_note["idNote"]]["comments"] = $note["comments"];
		}
        $last_add_note = $note;
    }
    else {echo "trabl"; exit();}
}

$smarty->assign('notes',$notes);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('m',$owner);
$smarty->assign('numTab', $owner != true ? 11 : 12);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_notes.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionNoteSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/notes.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");

  // echo '</br> 1 : '.Yii::app()->baseUrl;
  // echo '</br> 2 : '.Yii::app()->createUrl('1/logout', array('cherry'=>'23456'));
  // echo '</br> 3 : '.Yii::app()->getRequest()->getUrl();
  // echo '</br> 4 : '.Yii::app()->getRequest()->getHostInfo();
  // echo '</br> 5 : '.Yii::app()->getRequest()->getPathInfo();
  // echo '</br> 6 : '.Yii::app()->getRequest()->getRequestUri();
  // echo '</br> 7 : '.Yii::app()->getRequest()->getQueryString();
  // echo '</br> 7 : '.preg_replace('#^/pages#', '/auth', Yii::app()->createUrl('1/logout', array('cherry'=>'23456')));
?>