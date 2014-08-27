<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['countContinuation']))   { $countContinuation=$_POST['countContinuation'];}
if (isset ($_POST['idAlbumLast']))   { $idAlbumLast=$_POST['idAlbumLast'];}
if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}

//$p = $DB->getRow(QS::$q3, $idProfLoad);
//$ProfileLoad = new Profile($idProfLoad, $p);

//$author = $ProfileLoad->FIO();
$i = 0; // Переменная для индексации элементов массива "$albums"
if(isset ($idProfLoad) && isset ($idAlbumLast) && isset ($countContinuation) && 
    !empty ($idProfLoad) && !empty ($countContinuation) && !empty ($idAlbumLast))
{	
	 $q = $DB->getAll(QS::$getLoadedAlbums, $idProfLoad, $idProfLoad, $idAlbumLast, $countContinuation);

	$ProfileAuth = $DB->getRow(QS::$q3, $idProfAuth);
    $profileAuthUser = new Profile($idProfAuth, $ProfileAuth);
    $profilePathAvatar = $profileAuthUser->ProfilePathAvatar();
	 if($q)
    {
    	foreach($q as $key => $valueAllAlbums) // array_reverse($p) - для реверсирования  прохождения массива
        { 
    	   	// Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
           	$like = $DB->getOne(QS::$getLikeAlbum, $idProfLoad, $idProfLoad, $idProfAuth, $idProfLoad, $valueAllAlbums["id"]);
           	//print_r("like - ".$like);
           	$allCommentsQ = $DB->getAll(QS::$getCommentsAlbum, $idProfLoad, $idProfLoad, $valueAllAlbums["id"],
           													   $idProfLoad, $idProfLoad, $valueAllAlbums["id"],
           													   SETTING_COUNT_FIRST_LOAD_COMMENTS);
		    foreach($allCommentsQ as $valueAllComments)
		    {   
		    // Делаем запрос на получение полной информации об авторе комментария
		    $authorInfo = $DB->getRow(QS::$q3, $valueAllComments["profile_id"]);
		    // Создаем новый объект автора комментария
		    $authorComment = new Profile($valueAllComments["profile_id"], $authorInfo);
		    
		    // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
		    $like_com = $DB->getOne(QS::$getLikeCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $valueAllComments["id"]);

		    // id комента на который отвечают
		    $answerCommentID = $valueAllComments["commentalbum".$idProfLoad."_id"];
		    //print_r($answerCommentID);
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
		        preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueAllComments["text"], $matches);
		        // Обращение безя запятой
		        if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
		        // текст без обращения и запятой
		        $valueAllComments["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueAllComments["text"]);
		    }

		    $comment = array(
		        "id" => $valueAllComments["id"],
		        "datetime" => $valueAllComments["datetime"],
		        "text" => $valueAllComments["text"],
		        "countLike" => $valueAllComments["countlike"],
		        "albumNNN_id" => $valueAllComments["album".$idProfLoad."_id"],
		        "commentAlbumNNN_id" => $valueAllComments["commentalbum".$idProfLoad."_id"],
		        "profile_id" => $valueAllComments["profile_id"],
		        "authorCommentFIO" => $authorComment->FI(),
		        "authorCommentPathAvatar" => $authorComment->ProfilePathAvatar(),
		        "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
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
			        "photoPathAlbum" => '../img/album99.jpg',  //  photopath
			        "profilePathAvatar" => $profilePathAvatar,
			        "name" => $valueAllAlbums["name"],   //  name
			        "description" => $valueAllAlbums["description"],  // description
					"countPhoto" => $valueAllAlbums["countphoto"],  //  
			        "countComment" => $valueAllAlbums["countcomment"],  //  countcomment
			        "countLike" =>$valueAllAlbums["countlike"], //   countlike
			        "isProfileAuthSetLike" =>$like["count"]>0 ? true:false,
			        "comments" => $comments
			    );
			$albums[$i]=$album;
			$i++;
			unset($comments);
		}
		
		$rez = array("loadalbum", $albums);
	}else $rez = array("loadalbum", null, false); 
} else $rez = array("unknown", null);
print json_encode($rez);
?>