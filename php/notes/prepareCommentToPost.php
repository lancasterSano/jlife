<?php


// Все комменты для одной записи 
$c = $q; $q = null;
$comments = array();
$last_add_comment = null;
foreach($c as $key => $p)
{
    if($p["extension"] == null)
    { 
        // Автор коммента записи
        $authors_com = $DB->getRow(QS::$q3, $p["profile_id"]); 
        $Profile_authors_com = new Profile($p["profile_id"], $authors_com);
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
        $like_com = $DB->getOne(QS::$q8, $idProfLoad, $idProfLoad, $ProfileAuth->ID, $idProfLoad, $p["id"]);
        
        // id комента на который отвечают
        $answerCommentId = $p["commentwall".$idProfLoad."_id"];
        // Сам коммент на который отвечают (взят из списка выводимых уже комментов в UI)
        //$answerCommentFIO = $comments[$answerCommentId]["authorCommentFIO"];
        // если его нет - выгружаю из БД
        if($answerCommentId != null)
        {          
            $answerProfile = $DB->getRow(QS::$q18, $idProfLoad, $answerCommentId);
            $answerProfileID = $answerProfile["profile_id"];
            $author_comment_special = $DB->getRow(QS::$q5, $answerProfileID);
            $answerCommentFIO = $author_comment_special["firstname"]." ".$author_comment_special["lastname"];
            // полное обращение
            preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $p["text"], $matches);
            // Обращение безя запятой
            if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
            // текст без обращения и запятой
            $p["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $p["text"]);
        }
    }
// $ProfileAuth::getProjectPathAvatar($p["profile_id"],$authors_com["isdefaultava"],0)
    $datetime = setRussianDate(date("j n Y H i", strtotime($p["datetime"])));
    $comment = array(
        //"idNote" => $p["wall".$idProfLoad."_id"],
        "idComment" => $p["id"],
        "adress" => $matches_adress[0],
        "textComment" => $p["text"],
        "authorCommentID" => $p["profile_id"],
        "authorCommentFIO" => $Profile_authors_com->FI(),
        "authorCommentPathAvatar" => $Profile_authors_com->ProfilePathAvatar(),
        "dateComment" => $datetime,
        "countLikeComment" => $p["countlike"],
        "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
        "answerCommentId" => $answerCommentId,
        "answerAuthorCommentFIO" => $answerCommentFIO,
        "answerAuthorID" => $answerProfileID,
        "extension" => $p["extension"],
        "subCommnt" => array()
    );
    if($comment["extension"] == null)
    {                               
        $comments[$p["id"]] = $comment;
        $last_root_add_comment = $comment;      
    }
    else if($comment["extension"] != null)
    {
        $comments[$last_root_add_comment["idComment"]]["textComment"] .= $comment["textComment"];
        $comments[$last_root_add_comment["idComment"]]["authorCommentID"] .= $comment["authorCommentID"];
    }  
}
// var_dump($comments);
?>