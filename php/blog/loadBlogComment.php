<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['countContinuation']))   { $countContinuation=$_POST['countContinuation'];}
if (isset ($_POST['idBlogCommentLast']))   { $idBlogCommentLast=$_POST['idBlogCommentLast'];}
if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}

if(isset ($idProfLoad) && isset ($idBlogCommentLast) && isset ($countContinuation) && 
    !empty ($idProfLoad) && !empty ($countContinuation) && !empty ($idBlogCommentLast))
{
	if($idBlogCommentLast != 'null')
        $q = $DB->getAll(QS::$getLoadedPostsComment, $idProfLoad, $idProfLoad, $idBlogCommentLast, $countContinuation, $idProfLoad
        											, $idBlogCommentLast, $countContinuation, $idProfLoad
        											, $idBlogCommentLast, $countContinuation);
    //else
    //    $q = $DB->getAll(QS::$q4, $idProfLoad, $idProfLoad, $countContinuation);
        
    if($q)
    {
        foreach($q as $key => $value)
		{
		    // Делаем запрос на получение полной информации об авторе комментария
		    $authorInfo = $DB->getRow(QS::$q3, $value["profile_id"]);
		    // Создаем новый объект автора комментария
		    $authorComment = new Profile($value["profile_id"], $authorInfo);

		    // Делаем запрос на получение названия статьи, которой адресован комментарий
		    $postName = $DB->getRow(QS::$getPostNameInComment, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $value["blog".$idProfLoad."_id"]);

		    // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
		    $like_com = $DB->getOne(QS::$getLikeCommentBlog, $idProfLoad, $idProfLoad, $idProfAuth, $idProfLoad, $value["id"]);

		    // id комента на который отвечают
		    $answerCommentID = $value["commentblog".$idProfLoad->ID."_id"];
		    //print_r($answerCommentID);
		    if($answerCommentID != null)
		            {  
		                // Делаем запрос на получение id автора, которому адресован комментарий
		                $answerProfile = $DB->getRow(QS::$getProfileId, $idProfLoad->ID, $answerCommentID);
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
		//print_r($comments);
        $rez = array("loadblogcomments", $comments);
    }else $rez = array("loadblogcomments", null, false); 
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>