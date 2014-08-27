<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$id = $_POST["id"];
if (isset ($id) && !empty ($id)) {
    $profileFromDb = $DB->getRow(QS::$q3, $id);
    $profile = new Profile($id, $profileFromDb);
    $avatarPath = $profile->ProfilePathAvatar();
    $response = $avatarPath;
} 
print json_encode($response);
?>

