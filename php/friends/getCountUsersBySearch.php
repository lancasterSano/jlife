<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

if (isset ($_POST['searchKey']))   { $searchKey=$_POST['searchKey'];}

    if(isset($searchKey))
    {
        $searchKey = normalize(htmlspecialchars($searchKey))."%";
        $countUsers = $DB->getOne(QS::$searchCountUsersIdFromProfile, $searchKey, $searchKey);
    }
print json_encode($countUsers);
?>
