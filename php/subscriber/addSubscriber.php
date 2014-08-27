<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}

if(isset ($idProfileLoad)) {
    $p = $DB->getOne(QS::$checkSubscriberRelation, $ProfileAuth->ID, $idProfileLoad);
    if (!$p) {
        $DB->query(QS::$setSubscriptionFirst, $ProfileAuth->ID, $idProfileLoad);
        $DB->query(QS::$setSubscriberFirst, $idProfileLoad, $ProfileAuth->ID);
    } else {
        $DB->query(QS::$setMutualSubscriber, $ProfileAuth->ID, $idProfileLoad);
        $DB->query(QS::$setMutualSubscriber, $idProfileLoad, $ProfileAuth->ID);
        $profilefromDB = $DB->getRow(QS::$q3, $idProfileLoad);
        $profileSubscriber = new Profile($idProfileLoad, $profilefromDB);
        $res = array(
            "id" => $idProfileLoad,
            "friendFI" => $profileSubscriber->FI(),
            "avatarPath" => $profileSubscriber->ProfilePathAvatar()
        );
    }      
} else {
    $res = array("unknown", null);
}
print json_encode($res);
?>