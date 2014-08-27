<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';



if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['paragraph']))   { $paragraph=$_POST['paragraph'];}
if (isset ($_POST['name']))   { $name=$_POST['name'];}
if (isset ($_POST['text']))   { $text=$_POST['text'];}
if (isset ($_POST['number']))   { $number=$_POST['number'];}

if(isset ($idProfileAuth) && isset ($idProfileLoad) && $idProfileAuth == $idProfileLoad && isset ($name) && isset ($text))
{
	$res = $DB_DO->query("CALL cPartparagraph(?i,?s,?s,?i)", intval($number), $name, $text, intval($paragraph));
} 
else 
{
    $res ="error";
}
print json_encode($res);
?>
