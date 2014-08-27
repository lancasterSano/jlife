<?php
// Все комменты для одной записи 

$comments = array();
$last_add_comment = null;
foreach($c as $key => $p)
{
    if($p["extension"] == null)
    { 
        // Автор коммента записи
        $authors_com = $DB->getRow(QS::$q5, $p["profile_id"]);
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
        $like_com = $DB->getOne(QS::$q8, $idProfLoad, $idProfLoad, $ProfileAuth->ID, $idProfLoad, $p["id"]);

        // id комента на который отвечают
        $answerCommentId = $p["commentwall".$idProfLoad."_id"];
        // Сам коммент на который отвечают (взят из списка выводимых уже комментов в UI)
        //$answerCommentFIO = $comments[$answerCommentId]["authorCommentFIO"];
        // если его нет - выгружаю из БД
        if($answerCommentId != null)
        {
            $answerProfileId = $DB->getOne(QS::$q18, $idProfLoad, $answerCommentId);
            $author_comment_special = $DB->getRow(QS::$q5, $answerProfileId);
            $answerCommentFIO = $author_comment_special["firstname"]." ".$author_comment_special["lastname"];
        }
    }

    // $ProfileAuth::getProjectPathAvatar($p["profile_id"],$authors_com["isdefaultava"],0),
    // $authors_com["firstname"]." 2 ".$authors_com["lastname"].' ['.$ProfileAuth::getProjectPathAvatar($p["profile_id"],$authors_com["isdefaultava"],0).']',
    $comment = array(
        "idNote" => $p["wall".$idProfLoad."_id"],
        "idComment" => $p["id"],
        "textComment" => $p["text"],
        "authorCommentID" => $p["profile_id"],
        "authorCommentFIO" => $authors_com["firstname"]." ".$authors_com["lastname"],
        "authorCommentPathAvatar" => $ProfileAuth::getProjectPathAvatar($p["profile_id"],$authors_com["isdefaultava"],0),
        "dateComment" => $p["datetime"],
        "countLikeComment" => $p["countlike"],
        "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
        "answerCommentId" => $answerCommentId,
        "answerAuthorCommentFIO" => $answerCommentFIO,
        "extension" => $p["extension"],
        "subCommnt" => array()
    );

    if($comment["extension"] == null)
    {                               
        $comments[$p["id"]] = $comment;
        $last_root_add_comment = $comment;
        $last_add_comment = $comment;
    }
    else if($comment["extension"] != null)
    {
        $comments[$last_add_comment["idComment"]]["textComment"] .= $comment["textComment"];
        $last_add_comment = $comment;
    }
}
?>