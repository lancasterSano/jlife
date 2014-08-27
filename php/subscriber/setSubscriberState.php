<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if(isset($_POST['idLoad'])){ $idProfileLoad=$_POST['idLoad']; }
if(isset($_POST['action'])){ $action=$_POST['action']; }

if(isset($idProfileLoad) && isset($action)) {
    $profilefromDB = $DB->getRow(QS::$q3, $idProfileLoad);
    $profileSubscriber = new Profile($idProfileLoad, $profilefromDB);
    $resSubscriber = array(
        "id" => $idProfileLoad,
        "FI" => $profileSubscriber->FI(),
        "avaPath" => $profileSubscriber->ProfilePathAvatar(),
    );
    if($action == "subscribe") { //подписаться
        $isSubscriberRelation = $DB->getOne(QS::$checkSubscriberRelation, $ProfileAuth->ID, $idProfileLoad);
        if (!$isSubscriberRelation) {
            $DB->query(QS::$setSubscriptionFirst, $ProfileAuth->ID, $idProfileLoad); //set state = 1 to owner's  table
            $DB->query(QS::$setSubscriberFirst, $idProfileLoad, $ProfileAuth->ID); // set state = 2 to subscriber's table
            $oldrelation = null;
            $newrelation = 1;
        } else {
            $DB->query(QS::$setMutualSubscriber, $ProfileAuth->ID, $idProfileLoad); // set state = 0 to owner's table and subscriber's table
            $DB->query(QS::$setMutualSubscriber, $idProfileLoad, $ProfileAuth->ID);
            $oldrelation = 2;
            $newrelation = 0;
        }
    } else if($action == "unsubscribe"){ //отписаться
        $isMutualRelation = $DB->getOne(QS::$checkMutualRelation, $ProfileAuth->ID, $idProfileLoad); // check if subscriber and owner are mutual (state = 0)
        $isSubscriptionRelation = $DB->getOne(QS::$checkSubscriptionRelation, $ProfileAuth->ID, $idProfileLoad); // check if owner wants to unsubscribe from subscriber (state = 1)
        if($isMutualRelation) {
            $DB->query(QS::$setSubscriber, $ProfileAuth->ID, $idProfileLoad); //set state = 2 to owner's table (i have unsubscribed, but he is still following me)
            $DB->query(QS::$setSubscription, $idProfileLoad, $ProfileAuth->ID); //set state = 1 to subscriber's table (i have unsubscribed, but he is still following me)
            $oldrelation = 0;
            $newrelation = 2;
        }
        if($isSubscriptionRelation) {
            $DB->query(QS::$deleteSubscriberRelation, $ProfileAuth->ID, $idProfileLoad); //delete rows from owner's table and subscriber's table
            $DB->query(QS::$deleteSubscriberRelation, $idProfileLoad, $ProfileAuth->ID);
            $oldrelation = 1;
            $newrelation = null;
        }
    }
    $response = array("oldrelation"=> $oldrelation,"newrelation" => $newrelation, "oldProfile" => $resSubscriber);
} else {
    $response = array("fail");
}
print json_encode($response);
?>