<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');

$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "messageSend");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();
$idOwner = $ProfileAuth->ID;
$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('ProfileLoad', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$smarty->assign('PageTitle', 'Сообщения '.$ProfileAuth->FI());

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
if(isset($_GET["id"])){ 
    $id = $_GET["id"];
    if(isset($_GET["from"])){
        $from = $_GET["from"];
        switch ($from) {
            case "inbox":
                $partner = "sender";
                break;
            case "outbox":
                $partner = "recepient";
                break;
            default:
                header("Location: messages.php?type=inbox");
                break;
        }
        $text = null;
        $message = $DB->getAll(QS::$getMessage, $idOwner, $id, $id);
        $currentMessage = array();
        foreach ($message as $messagePart) {
            if(!$messagePart["extension"]){ //message is not extension
                $partnerID = $messagePart["id".$partner];
                $partnerFromDB = $DB->getRow(QS::$q3, $partnerID);
                $partnerProfile = new Profile($partnerID, $partnerFromDB);
                
                $text = $messagePart["text"];
                $date = $messagePart["date"];
                $state = $messagePart["new"]; // if i came here from inbox i will get 0 - if i have read this message or id of my partner's row (same message)
                
                $partnerProfileLink = "/pages/index.php?id=".$partnerID;
                $partnerFI = $partnerProfile->FI();
                $partnerAvatarPath = $partnerProfile->ProfilePathAvatar();
                if($state > 0 && $partner == "sender")
                {
                    $query = $DB->query(QS::$updateStatusRead, $partnerID, $state);
                    $query = $DB->query(QS::$updateStatusRead, $idOwner, $id);                
                }
                //FORM THE RESULTING ARRAY
                $currentMessage["date"] = $date;
                $currentMessage["partnerProfileLink"] = $partnerProfileLink;
                $currentMessage["partnerFI"] = $partnerFI;
                $currentMessage["partnerAvatarPath"] = $partnerAvatarPath;
                $currentMessage["partnerID"] = $partnerID;
                //END FORM THE RESULTING ARRAY
            }
            else {
                //APPEND TO TEXT EXTENSIONS
                $text = $text.$messagePart["text"];
            }
        }   
        $currentMessage["text"] = $text;
    }
    else {
            header("Location: messages.php?type=inbox");
    }
}
else {
    if(isset($_GET["from"])) {
        $from = $_GET["from"];
        if($from == "new") {
            $currentMessage = array();
            if(isset($_GET["rec"]) && !empty($_GET["rec"])) {
                // if we want to send message directly to some friend
                $partnerID = $_GET["rec"];
                $result = $DB->getOne(QS::$q2, $partnerID);
                if($result["count"] == 0) {
                    header("Location: messages.php?type=inbox");
                }
                $partnerFromDB = $DB->getRow(QS::$q3, $partnerID);
                $partnerProfile = new Profile($partnerID, $partnerFromDB);
                $partnerProfileLink = "/pages/index.php?id=".$partnerID;
                $partnerFI = $partnerProfile->FI();
                $partnerAvatarPath = $partnerProfile->ProfilePathAvatar();
                //FORM THE RESULTING ARRAY
                $currentMessage["partnerProfileLink"] = $partnerProfileLink;
                $currentMessage["partnerFI"] = $partnerFI;
                $currentMessage["partnerAvatarPath"] = $partnerAvatarPath;
                $currentMessage["partnerID"] = $partnerID;
                //END FORM THE RESULTING ARRAY
            }
            else {
                //if we want to send new empty message and no friend chosen
                $defaultAvatarPath = "/img/defaultSendMessage.jpg";
                //FORM THE RESULTING ARRAY
                $currentMessage["partnerAvatarPath"] = $defaultAvatarPath;
                //END FORM THE RESULTING ARRAY
            }
        }
        else {
            header("Location: messages.php?type=inbox");
        }
    }
    else {
        header("Location: messages.php?type=inbox");
    }
    
}

$smarty->assign('numTab', 23);
$smarty->assign("currentMessage", $currentMessage);
$smarty->assign("countInboxMsg", $ProfileAuth->countinbox);
$smarty->assign("countOutboxMsg", $ProfileAuth->countoutbox);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_messageSend.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionMessageSendSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$msgMainField_tpl = $smarty->fetch("./mainContent/mainfield/messageSend.tpl"); $smarty->assign('block_mainField', $msgMainField_tpl);

/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
