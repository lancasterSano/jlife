<?php
//$q = $DB->getAll(QS::$q4, $idProfLoad);
$nc = $q; $q = null;
foreach($nc as $key => $value)
{
    //mine
    if($value['new'])
    {
        $messages[$i]["message_class_css"] = "messageUnR";
    }
    else
    {
        $messages[$i]["message_class_css"] = "message";
    }
    $messages[$i]["profile_link"] = "/pages/index.php?id=".$value['idsender'];
    $messages[$i]["path_to_avatar"] = "/img/".$value['idsender'].".jpg";
    $profileSender = $DB->getRow(QS::$q3, $value["idsender"]);
    $messages[$i]["sender_name_surname"] = $profileSender["firstname"]." ".$profileSender["lastname"];
    $msgText = $value["text"];
    if(strlen($msgText) > 256)
    {
        $subText = mb_substr($msgText, 0, 160)."...";
        $messages[$i]["messageText"] = $subText;
    }
    else
    {
        $messages[$i]["messageText"] = $msgText;
    }
    $messages[$i]["dateSent"] = $value["datetime"];
    $messages[$i]["idmsg"] = $value["id"];
    $messages[$i]["idProfile"] = $ProfileID;
    $messages[$i]["expandMsg"] = "/pages/messageSend.php?idMsg=".$value["id"]."&from=inbox";
    
    
    
    //Cherry
    $like = null;
    if($value["extension"] == null)
    {  
        // Автор записи
        $authors = $DB->getRow(QS::$q5, $value["idauthor"]);
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на записи
        $like = $DB->getOne(QS::$q6, $idProfLoad, $idProfLoad, $ProfileAuth->ID, $idProfLoad, $value["id"]);

        $q = $DB->getAll(QS::$q15_1, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $value["id"], $idProfLoad, $idProfLoad, $value["id"], SETTING_COUNT_FIRST_LOAD_COMMENTS);

        require("prepareCommentToPost.php");
    }
    // htmlspecialchars
    $note = array(
        "idNote" => $value["id"],
        "textNote" => $value["text"],
        "authorNoteID" => $value["idauthor"],
        "authorNoteFIO" => $authors["firstname"]." ".$authors["lastname"],
        "authorNotePathAvatar" => $ProfileAuth::getProjectPathAvatar($value["idauthor"],$authors["isdefaultava"],0),
        "dateNote" => $value["datetime"],
        "countLikeNote" => $value["countlike"],
        "countComments" => $value["countcomment"],
        "isProfileAuthSetLike" => ($like["count"]>0 ? true:false),
        "extension" => $value["extension"],
        "comments" => $comments
    );
    
    if($last_add_note == null || $last_add_note["extension"] == null)
    {
        // а это уже новое
        $notes["id".$value["id"]] = $note;
        $last_root_add_note = $note;
        $last_add_note = $note;
    }
    else if($last_add_note["extension"] != null)
    {
        // нужно дописать
        $notes["id".$last_root_add_note["idNote"]]["textNote"] .= $note["textNote"];
		if($note["extension"] == null)
        {
            $notes["id".$last_root_add_note["idNote"]]["idNote"] = $note["idNote"];
            $notes["id".$last_root_add_note["idNote"]]["authorNoteID"] = $note["authorNoteID"];
            $notes["id".$last_root_add_note["idNote"]]["authorNoteFIO"] = $note["authorNoteFIO"];
            $notes["id".$last_root_add_note["idNote"]]["authorNotePathAvatar"] = $note["authorNotePathAvatar"];
            $notes["id".$last_root_add_note["idNote"]]["countLikeNote"] = $note["countLikeNote"];
            $notes["id".$last_root_add_note["idNote"]]["countComments"] = $note["countComments"];
            $notes["id".$last_root_add_note["idNote"]]["isProfileAuthSetLike"] = $note["isProfileAuthSetLike"];
            $notes["id".$last_root_add_note["idNote"]]["comments"] = $note["comments"];
		}
        $last_add_note = $note;
    }
    else {echo "trabl"; exit();}
}
?>