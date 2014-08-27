<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

$idAuth = $ProfileAuth->ID;
if (isset ($_POST['f']))   { $idFriend =  intval($_POST['f']);}
$groupsIDsFromDB = $DB->getAll(QS::$getGroupsOfFriend, $idAuth, $idAuth, $idAuth, $idFriend);
if($groupsIDsFromDB){
    foreach($groupsIDsFromDB as $groupsIDFromDB){
        $response[] = $groupsIDFromDB["idgroup"];
    }
} else {
    $response = null;
}
print json_encode($response);
?>
