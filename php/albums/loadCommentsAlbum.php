<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}
if (isset ($_POST['idAlbum']))   { $idAlbum=$_POST['idAlbum'];}
if (isset ($_POST['idCurAComment']))   { $idCurAComment = $_POST['idCurAComment']; }
if(isset ($idProfLoad) && isset ($idCurAComment) && isset($idAlbum) && isset($idProfAuth))
{
    $q = $DB->getAll(QS::$getCommentsExpand, $idProfLoad, $idProfLoad, $idAlbum, $idCurAComment);
    if($q)
    {
    	foreach($q as $valueComments)
        {   
        // Делаем запрос на получение полной информации об авторе комментария
        $authorInfo = $DB->getRow(QS::$q3, $valueComments["profile_id"]);
        // Создаем новый объект автора комментария
        $authorComment = new Profile($valueComments["profile_id"], $authorInfo);
        
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
        $like_com = $DB->getOne(QS::$getLikeCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $valueComments["id"]);

        // id комента на который отвечают
        $answerCommentID = $valueComments["commentalbum".$idProfLoad."_id"];
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
            preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueComments["text"], $matches);
            // Обращение безя запятой
            if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
            // текст без обращения и запятой
            $valueComments["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueComments["text"]);
        }

        $dateTimeComment = setRussianDate(date("j n Y H i", strtotime($valueComments["datetime"])));
        $comment = array(
            "id" => $valueComments["id"],
            //"adress" => $matches_adress[0],
            "datetime" => $dateTimeComment,
            "text" => $valueComments["text"],
            "countLike" => $valueComments["countlike"],
            "albumNNN_id" => $valueComments["album".$idProfLoad."_id"],
            "commentAlbumNNN_id" => $valueComments["commentalbum".$idProfLoad."_id"],
            "profile_id" => $valueComments["profile_id"],
            "authorCommentFIO" => $authorComment->FI(),
            "authorCommentPathAvatar" => $authorComment->ProfilePathAvatar(),
            "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
            "answerCommentId" => $answerCommentID,
            "answerAuthorCommentFIO" => $answerCommentFIO,
            "answerAuthorID" => $answerProfileID,
            "extension" => $valueComments["extension"]
        );
        if($comment["extension"] == null)
                {                                              
                    $comments[$valueComments["id"]]=$comment;
                    $root_comment = $comment;
                    //$last_add_comment = $comment;
                }
        else if($comment["extension"] != null)
                {
                    $comments[$root_comment["id"]]["text"] .= $comment["text"];
                }
        //$comments[$valueAllComments["id"]]=$comment;
        }
        //require("prepareCommentToPost.php");                  
        $rez = array("getcomment", $comments);
    }else $rez = array("getcomment", $p);    
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>