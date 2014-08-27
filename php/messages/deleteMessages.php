<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['ids'])) { $ids = json_decode(stripslashes($_POST['ids'])); }
if (isset ($_POST['idOwner'])) { $idOwner = $_POST['idOwner']; }
if (isset ($ids) && isset($idOwner)) {
    $result_array = array();
    foreach ($ids as $id) {
        $result_array[] = $id;
    }
    $query_result = $DB->query(QS::$deleteMessages, $idOwner, $result_array);
    if($query_result) {
        $response = array("success", $ids);
    } else {
        $response = array("fail", $ids);
    }
}
print json_encode($response);

?>
