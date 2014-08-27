<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if(isset($_POST["idOwner"])) {
    $idOwner = $_POST["idOwner"];
    $response = $DB->getOne(QS::$getCountFriendRequests, $idOwner, $idOwner);
}
print json_encode($response);
?>