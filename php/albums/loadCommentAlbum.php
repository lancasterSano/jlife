<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}
if (isset ($_POST['idAlbum']))   { $idAlbum=$_POST['idAlbum'];}
if (isset ($_POST['idCurNComment']))   { $idCurNComment = $_POST['idCurNComment']; }
if(isset ($idProfLoad) && isset ($idCurNComment) && isset($idAlbum) && isset($idProfAuth))
{
    $q = $DB->getAll(QS::$getOneMoreCommentAfterDelete, $idProfLoad, $idProfLoad, $idAlbum, $idProfLoad, $idProfLoad,
    													$idAlbum, $idCurNComment, $idCurNComment);
    if($q)
    {
    	foreach($q as $valueComment)
	    {   
		    if($p["extension"] == null)
    		{
			    // Делаем запрос на получение полной информации об авторе комментария
			    $authorInfo = $DB->getRow(QS::$q3, $valueComment["profile_id"]);
			    // Создаем новый объект автора комментария
			    $authorComment = new Profile($valueComment["profile_id"], $authorInfo);
			    
			    // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
			    $like_com = $DB->getOne(QS::$getLikeCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad,
			    												  $valueComment["id"]);

			    // id комента на который отвечают
			    $answerCommentID = $valueComment["commentalbum".$ProfileLoad->ID."_id"];
			    
			    if($answerCommentID != null)
			    {  
			        // Делаем запрос на получение id автора, которому адресован комментарий
			        $answerProfile = $DB->getRow(QS::$getProfileId_2, $idProfLoad, $answerCommentID);
			        // Получаем id автора
			        $answerProfileID = $answerProfile["profile_id"];
			        // Делаем запрос на получение информации об авторе, которому адресован комментарий
			        $author_comment_special = $DB->getRow(QS::$getProfileFI_2, $answerProfileID);
			        // Получаем Имя и Фамилию автора, к которому адресован комментарий
			        $answerCommentFIO = $author_comment_special["firstname"]." ".$author_comment_special["lastname"];
			        // полное обращение
			        preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueComment["text"], $matches);
			        // Обращение безя запятой
			        if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
			        // текст без обращения и запятой
			        $valueComment["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueComment["text"]);
			    }
			}

			$datetime = setRussianDate(date("j n Y H i", strtotime($valueComment["datetime"])));
		    $comment = array(
		        "id" => $valueComment["id"],
		        "adress" => $matches_adress[0],
		        "datetime" => $datetime,
		        "text" => $valueComment["text"],
		        "countLike" => $valueComment["countlike"],
		        "albumNNN_id" => $valueComment["album".$authorComment->ID."_id"],
		        "commentAlbumNNN_id" => $valueComment["commentalbum".$authorComment->ID."_id"],
		        "profile_id" => $valueComment["profile_id"],
		        "authorCommentFIO" => $authorComment->FI(),
		        "authorCommentPathAvatar" => $authorComment->ProfilePathAvatar(),
		        "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
		        "answerCommentId" => $answerCommentID,
        		"answerAuthorCommentFIO" => $answerCommentFIO,
        		"answerAuthorID" => $answerProfileID,
		        "extension" => $valueComment["extension"]
		    );
		    if($comment["extension"] == null)
		    {                               
		        $comments[$valueComment["id"]] = $comment;
		        $last_root_add_comment = $comment;      
		    }
		    else if($comment["extension"] != null)
		    {
		        $comments[$last_root_add_comment["id"]]["text"] .= $comment["text"];
		        $comments[$last_root_add_comment["id"]]["profile_id"] .= $comment["profile_id"];
		    }
	    }
	    $rez = array("getcomment", $comments);
	}else $rez = array("getcomment", $p);    
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>