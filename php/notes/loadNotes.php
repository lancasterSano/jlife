<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=intval($_POST['idLoad']);}
if (isset ($_POST['countContinuation']))   { $countContinuation=intval($_POST['countContinuation']);}
if (isset ($_POST['idNoteLast']))   { $idNoteLast=$_POST['idNoteLast'];}

if(isset ($idProfLoad) && isset ($idNoteLast) && isset ($countContinuation) && 
    !empty ($idProfLoad) && !empty ($countContinuation) && !empty ($idNoteLast))
{
    $ProfileAuth = $UA->getProfile();

    if($idNoteLast != 'null')
        $q = $DB->getAll(QS::$q21, $idProfLoad, $idProfLoad, $idNoteLast, $countContinuation, $idNoteLast);
    else
        $q = $DB->getAll(QS::$q4, $idProfLoad, $idProfLoad, $countContinuation);
        
    if($q)
    {
        //for($i=0;$i<9999999;$i++) {}
        require("prepareNoteToPost.php");
        //$continuation = true;      
        //var_dump($notes);
        $rez = array("loadnotes", $notes);
    }else $rez = array("loadnotes", null, false); 
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>