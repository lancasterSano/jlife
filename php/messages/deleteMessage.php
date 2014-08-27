<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['id'])) { $id = $_POST['id']; }
if (isset ($_POST['idOwner'])) { $idOwner = $_POST['idOwner']; }
if (isset ($id) && isset($idOwner)) {
    $query_result = $DB->query(QS::$deleteMessage, $idOwner, $id);
    if($query_result) {
        $response = "success";
    } else {
        $response = "fail";
    }
}
print json_encode($response);

?>