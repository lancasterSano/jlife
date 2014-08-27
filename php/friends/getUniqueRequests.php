<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();
if(isset($_POST["g"])){$idGroup = $_POST["g"];}
$r = $DB->getCol(QS::$getUniqueOutboxRequestsToGroup, $ProfileAuth->ID, $ProfileAuth->ID, $idGroup,$ProfileAuth->ID, $ProfileAuth->ID, $idGroup);
if($r)
    $response = $r;
else
    $response = null;
print json_encode($response);
?>
