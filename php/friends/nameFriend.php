<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}

if(isset ($idProfileAuth) && isset ($idProfileLoad))
{
    $pick = $DB->getAll(QS::$qfriend12, $idProfileAuth, $idProfileAuth, $idProfileLoad);
    $count = $DB->getOne(QS::$qfriend15, $idProfileAuth);
    $contact = $DB->getRow(QS::$qfriend9, $idProfileLoad);
    $groups = $DB->getAll(QS::$qfriend7, $idProfileAuth);
    $res = array(
        "contact" => $contact,
        "groups" => $groups,
        "count" =>$count,
        "pick" => $pick,
        );
} 
else 
{
    $res ="error";
}
print json_encode($res);
?>