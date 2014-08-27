<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['me']))   { $myID = $_POST['me'];}
if (isset ($_POST['recepient']))   { $recepientID = $_POST['recepient'];}
if (isset ($_POST['text']))   { $text = $_POST['text']; }
if (isset ($myID) && isset ($recepientID) && isset ($text) && !empty ($myID) && !empty ($recepientID) && !empty ($text))
{
    $len_msg = 256;
    $text = normalize(htmlspecialchars($text));
    if((mb_strlen($text) - $len_msg) > 0){
        $c = (int)(mb_strlen($text)/$len_msg);
        $messageParts = array();
        for ($i = 0; $i <= $c; $i++) {
            $messageParts[$i] = mb_substr($text, $i*$len_msg, $len_msg);
        }
        for($i = 0; $i <= $c; $i++) {
            if($i == 0) {
                $q1 = $DB->query(QS::$insertMessage, $myID, $messageParts[$i], date("Y-m-d H-i-s"), $recepientID, $myID, null, 1);
                $idFirstInsertSender = $DB->insertId();
                $q2 = $DB->query(QS::$insertMessage, $recepientID, $messageParts[$i], date("Y-m-d H-i-s"), $recepientID, $myID, null, $idFirstInsertSender);
                $idFirstInsertReceiver = $DB->insertId();
            }
            else if($i != 0) {
                $q1 = $DB->query(QS::$insertMessage, $myID, $messageParts[$i], date("Y-m-d H-i-s"), $recepientID, $myID,$idFirstInsertSender, 0);
                $q2 = $DB->query(QS::$insertMessage, $recepientID, $messageParts[$i], date("Y-m-d H-i-s"), $recepientID, $myID, $idFirstInsertReceiver, 0);
            } 
        }
    }
    else { 
        $q1 = $DB->query(QS::$insertMessage, $myID, $text, date("Y-m-d H-i-s"), $recepientID, $myID, null, 1);
        $idFirstInsertSender = $DB->insertId();
        $q2 = $DB->query(QS::$insertMessage, $recepientID, $text, date("Y-m-d H-i-s"), $recepientID, $myID, null, $idFirstInsertSender);
        $idFirstInsertReceiver = $DB->insertId();
    }
    if((!empty ($idFirstInsertSender)) && (!empty ($idFirstInsertReceiver))) {      
        $rez = array("insertedmsg", $q1, "../pages/messages.php?type=inbox");
    }
    else {
        $rez = array("insertedmsg", null);
    }
}
else {
    $rez = array ("unknown", null, $q1, $q2);
}
print json_encode($rez)
?>
