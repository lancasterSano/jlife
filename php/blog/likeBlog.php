<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['idBlog']))   { $idBlog = $_POST['idBlog']; }
if (isset ($_POST['my']) && ($_POST['my']==25) ) $state = true;
else if (isset ($_POST['my']) && ($_POST['my']==30) ) $state = false;
else unset($state);

if(isset($state) && isset ($idProfileAuth) && isset ($idProfileLoad) && isset ($idBlog))
{
    if(!$state)
    {
        // ставим Like
        $p = $DB->query(QS::$insertLikeBlog, $idProfileLoad, $idProfileLoad, $idBlog, $idProfileAuth);
        $rez = array("insert", $p);
    }
    else
    {
        // убираем Like
        $p = $DB->query(QS::$deleteLikeBlog, $idProfileLoad, $idProfileLoad, $idProfileLoad, $idBlog, $idProfileLoad, $idProfileAuth);
        $rez = array("delete", $p);
    }
} else $rez = array("unkmnown", null);
print json_encode($rez);