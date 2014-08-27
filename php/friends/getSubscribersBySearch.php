<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

if(isset($_POST["idLoad"])){ $idLoad = $_POST["idLoad"];}
if(isset($_POST["searchKey"])) {$searchKey = $_POST["searchKey"];}

if(isset($idLoad) && isset($searchKey)) {
    $searchKey = $searchKey."%";
    
    $subscriptionsFromDB = $DB->getAll(QS::$getSearchedSubscriptions, $idLoad, $searchKey, $searchKey);
    foreach ($subscriptionsFromDB as $subscriptionFromDB) {
        $idContact = $subscriptionFromDB["id"];
        $contactFromDB = $DB->getRow(QS::$q3, $idContact);
        $profileContact = new Profile($idContact, $contactFromDB);
        $fiContact = $profileContact->FI();
        $avaPathContact = $profileContact->ProfilePathAvatar();
        $subscriptions[] = array(
            "id" => $idContact,
            "FI" => $fiContact,
            "avaPath" => $avaPathContact,
            "type" => 1
        );
    }
    
    
    $unmutualSubscribersFromDB = $DB->getAll(QS::$getSearchedUnmutualSubscribers, $idLoad, $searchKey, $searchKey);
    $mutualSubscribersFromDB = $DB->getAll(QS::$getSearchedMutualSubscribers, $idLoad, $searchKey, $searchKey);
    foreach ($unmutualSubscribersFromDB as $subscriberFromDB) {
        $idContact = $subscriberFromDB["id"];
        $contactFromDB = $DB->getRow(QS::$q3, $idContact);
        $profileContact = new Profile($idContact, $contactFromDB);
        $fiContact = $profileContact->FI();
        $avaPathContact = $profileContact->ProfilePathAvatar();
        $subscribers[] = array(
            "id" => $idContact,
            "FI" => $fiContact,
            "avaPath" => $avaPathContact,
            "type" => 2
        );
    }
    foreach ($mutualSubscribersFromDB as $subscriberFromDB) {
        $idContact = $subscriberFromDB["id"];
        $contactFromDB = $DB->getRow(QS::$q3, $idContact);
        $profileContact = new Profile($idContact, $contactFromDB);
        $fiContact = $profileContact->FI();
        $avaPathContact = $profileContact->ProfilePathAvatar();
        $subscribers[] = array(
            "id" => $idContact,
            "FI" => $fiContact,
            "avaPath" => $avaPathContact,
            "type" => 3
        );
    }
    $response = array("subscriptions" => $subscriptions, "subscribers" => $subscribers);
      
}
else {
    $response = array("fail", null);
}
print json_encode($response);
?>
