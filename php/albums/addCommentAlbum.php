<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['textCA']))   { $textCommentAlbum=$_POST['textCA'];}
if (isset ($_POST['idAlbum']))   { $idAlbum=$_POST['idAlbum'];}
if (isset ($_POST['idAnswer']))   { $idAnswer=$_POST['idAnswer'];}

if(isset ($idProfAuth) && isset ($idProfLoad) && isset ($textCommentAlbum) && isset ($idAlbum) && isset ($idAnswer)
    && !empty ($idProfAuth) && !empty ($idProfLoad) && !empty ($textCommentAlbum) && !empty ($idAlbum)
    )
{
    if($idAnswer==null) $idAnswer = null; 
    $len_msg = 256;
    $len_msg_real = 127;
    $textCommentAlbum = normalize(htmlspecialchars($textCommentAlbum));
    if( ((mb_strlen($textCommentAlbum)-$len_msg)) > 0 )
    {
        //echo ((strlen($textCommentNote)/$len_msg)+1); //exit();
        $countFullText = (int)(mb_strlen($textCommentAlbum)/$len_msg); // сколько полных минимальных text
        $fullCommentText = array();
        for($i=0; $i<=$countFullText; $i++)
        {
            $partCommentText = null;
            $partCommentText = mb_substr($textCommentAlbum, $i*$len_msg, $len_msg);
            //echo "C ".$i*$len_msg."  L=".$len_msg." L(b)=".strlen($text)."   ".$text."<br/><br/>";
            $fullCommentText[$i] = $partCommentText;            
        }
        // print_r($fullCommentText);
        // exit();
        for($i=0; $i<=$countFullText; $i++)
        {
            if($i==0)
            {
                $p = $DB->query(QS::$insertCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfAuth, date("Y-m-d H-i-s"), $fullCommentText[$i], $idAlbum, $idAnswer, null);
                $idFirstInsert = $DB->getOne(QS::$idFirstInsert, $idProfLoad, $idProfLoad, $idProfAuth);
            }
            else 
            {
                $p = $DB->query(QS::$insertCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfAuth, date("Y-m-d H-i-s"), $fullCommentText[$i], $idAlbum, $idAnswer, $idFirstInsert); 
            } 
        }
    }
    else 
    {
        $p = $DB->query(QS::$insertCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfAuth, date("Y-m-d H-i-s"), $textCommentAlbum, $idAlbum, $idAnswer, null);
        $idFirstInsert = $DB->getOne(QS::$idFirstInsert, $idProfLoad, $idProfLoad, $idProfAuth);
    }
    if(!empty ($idFirstInsert))
    {
        $getNewStringComment = $DB->getAll(QS::$getNewStringComment, $idProfLoad, $idProfLoad, $idAlbum, $idFirstInsert);
        //print_r($q);
        //exit();
        foreach($getNewStringComment as $valueComment)
        {   
        // Делаем запрос на получение полной информации об авторе комментария
        $authorInfo = $DB->getRow(QS::$q3, $valueComment["profile_id"]);
        // Создаем новый объект автора комментария
        $authorComment = new Profile($valueComment["profile_id"], $authorInfo);
        
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
        $like_com = $DB->getOne(QS::$getLikeCommentAlbum, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $valueComment["id"]);

        // id комента на который отвечают
        $answerCommentID = $valueComment["commentalbum".$idProfLoad."_id"];
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
            $answerCommentFIO = $author_comment_special["lastname"]." ".$author_comment_special["firstname"];
            // полное обращение
            preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueComment["text"], $matches);
            // Обращение безя запятой
            if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
            // текст без обращения и запятой
            $valueComment["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueComment["text"]);
        }
        
        $dateTimeComment = setRussianDate(date("j n Y H i", strtotime($valueComment["datetime"])));
        $comment = array(
            "id" => $valueComment["id"],
            //"adress" => $matches_adress[0],
            "datetime" => $dateTimeComment,
            "text" => $valueComment["text"],
            "countLike" => $valueComment["countlike"],
            "albumNNN_id" => $valueComment["album".$idProfLoad."_id"],
            "commentAlbumNNN_id" => $valueComment["commentalbum".$idProfLoad."_id"],
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
                    $comments[$valueComment["id"]]=$comment;
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
        //var_dump($comments);      
        $rez = array("insertedcomment", $comments, $idFirstInsert);
    }else $rez = array("insertedcomment", null); 
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>