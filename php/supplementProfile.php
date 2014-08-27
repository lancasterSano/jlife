<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idOwner'])) { $idOwner = $_POST['idOwner']; }
if (isset($idOwner)) {
	// Valid user
		$response['profile']['valid'] = $ProfileAuth->valid;
		$response['profile']['acceptlicense'] = $ProfileAuth->acceptlicense;
	// Count unread messages
	    $query = $DB->getOne(QS::$getCountUnread, $idOwner, $idOwner);
	    $count = $query;
	    $response['countUnread'] = array($count);

	// Count friends request 
	    $idOwner = $_POST["idOwner"];
	    $response['countFriendRequests'] = $DB->getOne(QS::$getCountFriendRequests, $idOwner, $idOwner);
}
// for ($i=0; $i < 50000000 / 1000000; $i++){ $j=$j+1; }
print json_encode($response);
?>