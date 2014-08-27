<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['key'])) { $key = $_POST['key']; }
if (isset ($_POST['idOwner'])) { $idOwner = $_POST['idOwner']; }
if (isset ($key) && isset($idOwner)) {
    if($key == "inbox") {
        $query = $DB->getOne(QS::$getCountInbox, $idOwner, $idOwner);
    }
    else if ($key == "outbox") {
        $query = $DB->getOne(QS::$getCountOutbox, $idOwner, $idOwner);
    }
    $count = $query[0];
    $response = $count;
}
print json_encode($response);

?>