<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
$idProfileAuth = $ProfileAuth->ID;
if(isset ($idProfileAuth) && isset ($idProfileLoad) && $idProfileAuth != $idProfileLoad){
    $relation = $DB->getOne(QS::$qfriend1, $idProfileAuth, $idProfileLoad);
    $X = $DB->getOne(QS::$qfriend16, $idProfileAuth, $idProfileLoad);
    if ($X && $relation == 2){
        $DB->query(QS::$updateFriendMutualState, $idProfileAuth, 3, $idProfileLoad);
        $res = true;
    }
} else { $res = false; }
print json_encode($res);
?>