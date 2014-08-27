<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}

if(isset ($idProfileAuth) && isset ($idProfileLoad)) {
    $pick = $DB->getAll(QS::$qfriend12, $idProfileAuth, $idProfileAuth, $idProfileLoad);
    $groups = $DB->getAll(QS::$qfriend8, $idProfileAuth);
     // $res = $groups;
    $res = array(
        "groups" => $groups,
     	"pick" => $pick,
    );
} else {
    $res ="error";
}
print json_encode($res);
?>