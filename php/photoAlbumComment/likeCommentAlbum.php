<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['idCommentAlbum']))   { $idComment = $_POST['idCommentAlbum']; }
if (isset ($_POST['idCommentPhoto']))   { $idComment = $_POST['idCommentPhoto']; }
if (isset ($_POST['typeLike']))   { $typeLike = $_POST['typeLike']; }
if (isset ($_POST['my']) && ($_POST['my']==25) ) $state = true;
else if (isset ($_POST['my']) && ($_POST['my']==30) ) $state = false;
else unset($state);

if(isset($state) && isset ($idProfileAuth) && isset ($idProfileLoad) && isset ($idComment) && isset ($typeLike))
{
    if($typeLike == "album")
    {
        if(!$state)
        {
            // ставим Like
            $p = $DB->query(QS::$insertLikeCommentAlbum, $idProfileLoad, $idProfileLoad, $idComment, $idProfileAuth);
            $rez = array("insert", $p);
        }
        else
        {
            // убираем Like
            $p = $DB->query(QS::$deleteLikeCommentAlbum, $idProfileLoad, $idProfileLoad, $idProfileLoad, $idComment, $idProfileLoad, $idProfileAuth);
            $rez = array("delete", $p);
        }
    }
    else if ($typeLike == "photo")
    {   
        if(!$state)
        {
            // ставим Like
            $p = $DB->query(QS::$insertLikeCommentPhoto, $idProfileLoad, $idProfileLoad, $idComment, $idProfileAuth);
            $rez = array("insert", $p);
        }
        else
        {
            // убираем Like
            $p = $DB->query(QS::$deleteLikeCommentPhoto, $idProfileLoad, $idProfileLoad, $idProfileLoad, $idComment, $idProfileLoad, $idProfileAuth);
            $rez = array("delete", $p);
        } 
    }  
}
else $rez = array("unkmnown", null);
print json_encode($rez);