<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['idOwner'])) { $idOwner = $_POST['idOwner']; }
if (isset($idOwner)) {
    $query = $DB->getOne(QS::$getCountUnread, $idOwner, $idOwner);
    $count = $query;
    $response = array($count);
}
print json_encode($response);
?>