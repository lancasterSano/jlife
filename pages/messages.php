<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
require_once(PROJECT_PATH.'/settings/settings.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "messages");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile(); //get Profile of page owner
$idOwner = $ProfileAuth->ID;
$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('ProfileLoad', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$smarty->assign('PageTitle', 'Сообщения '.$ProfileAuth->FI());

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
if(isset($_GET["type"])) {
    $type = $_GET["type"]; 
    switch ($type) {
        case "inbox": //type = inbox means that we want to show inbox messages
            $smarty->assign('numTab', 21);
            $msgFromDb = $DB->getAll(QS::$getInboxMessages, $idOwner, $idOwner, SETTING_COUNT_FIRST_LOAD_MESSAGES); //get all messages owned by $ProfileID where he is recepient
            $partner = "sender";
            $link_part = "inbox";
            $countActiveTabMsg = $ProfileAuth->countinbox;
            break;
        case "outbox": // type = outbox means that we want to show outbox messages
            $smarty->assign('numTab', 22);
            $msgFromDb = $DB->getAll(QS::$getOutboxMessages, $idOwner, $idOwner, SETTING_COUNT_FIRST_LOAD_MESSAGES);//get all messages owned by $ProfileID where he is a sender
            $partner = "recepient";
            $link_part = "outbox";
            $countActiveTabMsg = $ProfileAuth->countoutbox;
            break;
        default:
            header("Location: messages.php?type=inbox");
            break;
    }
    if($msgFromDb) {
        $isMsgInActiveTab = 1;
        foreach($msgFromDb as $message) {
            $partnerID = $message["id".$partner];
            $partnerFromDB = $DB->getRow(QS::$q3, $partnerID); //get profile of chat partner. 
                                                                            //if i am in inbox section - my partner is a sender else my partner is a recepient
            $partnerProfile = new Profile($partnerID, $partnerFromDB);
            
        //FORM THE ELEMENTS OF ARRAY
            $id = $message["id"];
            //1. get message text and cut if needed
            $msgTextFromDb = $message["text"]; // save message from DB to variable
            $temp = preg_split("/<br \/>/", $msgTextFromDb);
            if(mb_strlen($temp[0]) > 160) {
                $msgText = mb_substr($temp[0], 0, 160)."...";  //cut message to 160 symbols   
            }
            else {
                $msgText = $temp[0];
            }
            if(isset ($temp[1])) {
                $msgText.="...";
            }
            //end 1

            //2. Get other elements and modify related to partner's identity (sender or recepient)
            $datetime = setRussianDate(date("j n Y H i", strtotime($message["datetime"])));
            $state = $message["new"];
            $partnerProfileLink = "/pages/index.php?id=".$partnerID;
            $partnerFI = $partnerProfile->FI();
            $partnerAvatarPath = $partnerProfile->ProfilePathAvatar();
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
        //END FORM THE RESULTING ARRAY
        }
    }
}
 else {
    header("Location: messages.php?type=inbox");
}

$smarty->assign("idOwner", $idOwner);

$smarty->assign("isMsgInActiveTab", $isMsgInActiveTab);
$smarty->assign("messages", $messages);

$smarty->assign("countInboxMsg", $ProfileAuth->countinbox);
$smarty->assign("countOutboxMsg", $ProfileAuth->countoutbox);
$smarty->assign("countActiveTabMsg", $countActiveTabMsg);

$smarty->assign('SETTING_COUNT_FIRST_LOAD_MESSAGES', SETTING_COUNT_FIRST_LOAD_MESSAGES);
$smarty->assign('SETTING_COUNT_CONTINUATION_LOAD_MESSAGES', SETTING_COUNT_CONTINUATION_LOAD_MESSAGES);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_messages.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionMessagesSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$msgMainField_tpl = $smarty->fetch("./mainContent/mainfield/messages.tpl"); $smarty->assign('block_mainField', $msgMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
