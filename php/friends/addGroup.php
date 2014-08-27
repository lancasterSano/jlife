<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['group']))   { $group=$_POST['group'];}

if(isset ($idProfileAuth) && isset ($group) && isset ($idProfileLoad))
{
    $DB->query(QS::$qfriend10, $idProfileAuth, $group);
    $res = $idProfileLoad;
} 
else 
{
    $res ="error";
}
print json_encode($res);
?>