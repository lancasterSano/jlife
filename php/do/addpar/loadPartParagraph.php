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
if (isset ($_POST['partParagraph']))   { $partParagraph=$_POST['partParagraph'];}


if(isset ($idProfileAuth) && isset ($idProfileLoad) && $idProfileAuth == $idProfileLoad && isset ($partParagraph))
{
   $temp1 = $DB_DO->getRow(QSDO::$getPartParagraph, $partParagraph);
   $temp2 = $DB_DO->getAll(QSDO::$getNumbersOfPartParagraphs, $partParagraph);
   $res = array(
        "1" => $temp1,
        "2" => $temp2,
        );
} 
else 
{
    $res ="error";
}
print json_encode($res);
?>