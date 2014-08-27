<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();

if(isset($_POST["g"])){$idGroup = $_POST["g"];}
if(isset($_POST["n"])){$name = $_POST["n"];}

$r = $DB->query(QS::$updateGroupName, $ProfileAuth->ID, htmlspecialchars(normalize($name)), $idGroup);
if($r)
    $response = true;
else
    $response = false;
print json_encode($response);
?>
