<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['idAlbum']))   { $idAlbum = $_POST['idAlbum']; }
if (isset ($idProfileLoad) && isset ($idAlbum))
{
    $p = $DB->query(QS::$deleteAlbum, $idProfileLoad, $idProfileLoad, $idAlbum);
    $rez = array("delete", $p);
}
else $rez = array("unkmnown", null);
print json_encode($rez);