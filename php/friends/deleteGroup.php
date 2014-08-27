<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();

if(isset($_POST["g"])){$idGroup = $_POST["g"];}

$uniqueFriendsIds = $DB->getCol(QS::$getUniqueFriendsInGroup, $ProfileAuth->ID, $ProfileAuth->ID, $idGroup,$ProfileAuth->ID, $ProfileAuth->ID, $idGroup);
$uniqueOutboxRequestsIds = $DB->getCol(QS::$getUniqueOutboxRequestsToGroup, $ProfileAuth->ID, $ProfileAuth->ID, $idGroup,$ProfileAuth->ID, $ProfileAuth->ID, $idGroup);
$rdf = $DB->query(QS::$deleteFriendsFromGroup, $ProfileAuth->ID, $ProfileAuth->ID, $idGroup, $uniqueFriendsIds);
$rdg = $DB->query(QS::$deleteGroup, $ProfileAuth->ID, $idGroup);
foreach($uniqueFriendsIds as $uniqueFriendsId){
    $DB->query(QS::$updateFriendMutualState, $uniqueFriendsId, 1, $ProfileAuth->ID);
}
foreach($uniqueOutboxRequestsIds as $uniqueOutboxRequestsId){
    $DB->query(QS::$deleteFriendFromGroups, $uniqueOutboxRequestsId, $ProfileAuth->ID);
}
if($rdf && $rdg) $response = true;
else $response = false;
print json_encode($response);
?>
