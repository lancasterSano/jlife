<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idOwner']))      { $idOwner = $_POST['idOwner']; }
if (isset ($_POST['countCont']))    { $countCont = $_POST['countCont'];}
if (isset ($_POST['idMessageLast']))   { $idMsgLast = $_POST['idMessageLast'];}
if (isset ($_POST['key']))   { $key = $_POST['key'];}

if(isset ($idOwner) && isset ($idMsgLast) && isset ($countCont) && isset($key))
{
    if($key == "inbox") {
        $link_part = "inbox";
        $partner = "sender";
        if($idMsgLast != 'null')
            $msgFromDb = $DB->getAll(QS::$getInboxContinuationMessages, $idOwner, $idOwner, $idMsgLast, $idOwner, $countCont, $idMsgLast, $idOwner);
        else
            $msgFromDb = $DB->getAll(QS::$getInboxMessages, $idOwner, $idOwner, $countCont);
    }
    else if($key == "outbox") {
        $link_part = "outbox";
        $partner = "recepient";
        if($idMsgLast != 'null')
            $msgFromDb = $DB->getAll(QS::$getOutboxContinuationMessages, $idOwner, $idOwner, $idMsgLast, $idOwner, $countCont, $idMsgLast, $idOwner);
        else
            $msgFromDb = $DB->getAll(QS::$getOutboxMessages, $idOwner, $idOwner, $countCont);
    }
    if($msgFromDb) {
        foreach ($msgFromDb as $message) {
            $partnerProfile = $DB->getRow(QS::$q3, $message["id".$partner]); //get profile of chat partner. 
                                                                            //if i am in inbox section - my partner is a sender else my partner is a recepient
            $ProfilePartner = new Profile($message["id".$partner], $partnerProfile);
            //FORM THE ELEMENTS OF ARRAY
            $id = $message["id"];
            //1. get message text and cut if needed
            $msgTextFromDb = $message["text"]; // save message from DB to variable
            $temp = preg_split("/<br \/>/", $msgTextFromDb);
            if(mb_strlen($temp[0]) > 160) {
                $msgText = mb_substr($temp[0], 0, 160)."...";  //cut message to 160 symbols   
            }
            else { $msgText = $temp[0]; }
            if(isset ($temp[1])) {
                $msgText.="...";
            }
            //end 1

            //2. Get other elements and modify related to partner's identity (sender or recepient)
            $datetime = $message["datetime"];
            $state = $message["new"];
            $partnerProfileLink = "/pages/index.php?id=".$message["id".$partner];
            $partnerFI = $partnerProfile["firstname"]." ".$partnerProfile["lastname"];
            $partnerAvatarPath = $ProfilePartner->ProfilePathAvatar();
            $expandLink = "/pages/messageSend.php?id=".$message["id"]."&from=".$link_part;
            //end 2

            //END FORM ARRAY OF ELEMENTS

            //FORM THE RESULTING ARRAY
            $messages[] = array("id" => $id,
                                "text" => $msgText,
                                "date" => $datetime,
                                "state" => $state,
                                "partnerProfileLink" => $partnerProfileLink,
                                "partnerFI" => $partnerFI,
                                "partnerAvatarPath" => $partnerAvatarPath,
                                "expandLink" => $expandLink
                                );
        }
        $response = array("success", $messages);
    } else {
        $response = array("fail", null); 
    }
} else $response = array("unknown", null);
print json_encode($response);
?>