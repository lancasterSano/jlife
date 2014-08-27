<?php
//$q = $DB->getAll(QS::$q4, $idProfLoad);
$nc = $q; $q = null;
$notes = array();
foreach($nc as $key => $value)
{
    // Автор записи
    $authors = $DB->getRow(QS::$q3, $value["idauthor"]);
    $Profile_authors = new Profile($value["idauthor"], $authors);
    
    $like = null;
	if($value["extension"] == null)
	{  
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на записи
        $like = $DB->getOne(QS::$q6, $idProfLoad, $idProfLoad, $ProfileAuth->ID, $idProfLoad, $value["id"]);

        $q = $DB->getAll(QS::$q15_1, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $value["id"], $idProfLoad, $idProfLoad, $value["id"], SETTING_COUNT_FIRST_LOAD_COMMENTS);
        
        require("prepareCommentToPost.php");
    }
    // htmlspecialchars
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