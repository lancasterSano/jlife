<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['idCommentAlbum']))   { $idCommentAlbum = $_POST['idCommentAlbum']; }
if (isset ($_POST['my']) && ($_POST['my']==25) ) $state = true;
else if (isset ($_POST['my']) && ($_POST['my']==30) ) $state = false;
else unset($state);

if(isset($state) && isset ($idProfileAuth) && isset ($idProfileLoad) && isset ($idCommentAlbum))
{
    if(!$state)
    {
        // ставим Like
        $p = $DB->query(QS::$insertLikeCommentAlbum, $idProfileLoad, $idProfileLoad, $idCommentAlbum, $idProfileAuth);
        $rez = array("insert", $p);
    }
    else
    {
        // убираем Like
        $p = $DB->query(QS::$deleteLikeCommentAlbum, $idProfileLoad, $idProfileLoad, $idProfileLoad, $idCommentAlbum, $idProfileLoad, $idProfileAuth);
        $rez = array("delete", $p);
    }
} else $rez = array("unkmnown", null);
print json_encode($rez);