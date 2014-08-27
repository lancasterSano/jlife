<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if(isset($_POST['l'])) { $idProfileLoad = $_POST['l']; }
if(isset($_POST['g'])) { $idGroup = $_POST['g']; }

$group = $DB->getRow(QS::$qfriend6, $idProfileLoad, $idGroup);
$res = $group;
print json_encode($res);
?>