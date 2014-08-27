<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

if(isset($_POST["searchKey"])) {$searchKey = $_POST["searchKey"];}

if(isset($searchKey) && isset($idAuth)) {
    $searchKey = $searchKey."%";
    $requestsOutboxFromDb = $DB->getAll(QS::$getSearchedOutboxRequests, $idAuth, $searchKey, $searchKey);
    foreach ($requestsOutboxFromDb as $requestOutboxFromDb) {
        $idContact = $requestOutboxFromDb["id"];
        $contactFromDB = $DB->getRow(QS::$q3, $idContact);
        $profileContact = new Profile($idContact, $contactFromDB);
        $fiContact = $profileContact->FI();
        $avaPathContact = $profileContact->ProfilePathAvatar();
        $outboxRequests[] = array(
            "id" => $idContact,
            "FI" => $fiContact,
            "avaPath" => $avaPathContact,
            "type" => 1
        );
    }
    
    $requestsInboxNewFromDb = $DB->getAll(QS::$getSearchedInboxNewRequests, $idAuth, $searchKey, $searchKey);
    foreach ($requestsInboxNewFromDb as $requestInboxNewFromDb) {
        $idContact = $requestInboxNewFromDb["id"];
        $contactFromDB = $DB->getRow(QS::$q3, $idContact);
        $profileContact = new Profile($idContact, $contactFromDB);
        $fiContact = $profileContact->FI();
        $avaPathContact = $profileContact->ProfilePathAvatar();
        $inboxRequests[] = array(
            "id" => $idContact,
            "FI" => $fiContact,
            "avaPath" => $avaPathContact,
            "type" => 2
        );
    }
    
    $requestsInboxOldFromDb = $DB->getAll(QS::$getSearchedInboxOldRequests, $idAuth, $searchKey, $searchKey);
    foreach ($requestsInboxOldFromDb as $requestInboxOldFromDb) {
        $idContact = $requestInboxOldFromDb["id"];
        $contactFromDB = $DB->getRow(QS::$q3, $idContact);
        $profileContact = new Profile($idContact, $contactFromDB);
        $fiContact = $profileContact->FI();
        $avaPathContact = $profileContact->ProfilePathAvatar();
        $inboxRequests[] = array(
            "id" => $idContact,
            "FI" => $fiContact,
            "avaPath" => $avaPathContact,
            "type" => 3
        );
    }
    
    $response = array("outboxRequests" => $outboxRequests,
                      "inboxRequests" => $inboxRequests);
} else {
    $response = null;
}
print json_encode($response);
?>
