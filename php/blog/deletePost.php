<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if(isset($_POST['idPost']))
{
	$idPost = $_POST['idPost'];
}
if(isset($_POST['idOwner']))
{
	$idOwner = $_POST['idOwner'];
}


$p = $DB->query(QS::$deletePost, $idOwner, $idPost);
///$p2 = $DB->getAll(QS::$getMetkasInBox, $idOwner);
//$response = array("contents" => $contents, "author" => $author);

print json_encode($p);

?>