<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['idNote']))   { $idNote=$_POST['idNote'];}
if (isset ($_POST['idCurNComment']))   { $idCurNComment = $_POST['idCurNComment']; }
if(isset ($idProfLoad) && isset ($idCurNComment) && isset($idNote))
{
    $q = $DB->getAll(QS::$q17, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $idNote, $idCurNComment);
    
    if($q)
    {
        require("prepareCommentToPost.php");                  
        $rez = array("getcomment", $comments);
    }else $rez = array("getcomment", $p);    
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>