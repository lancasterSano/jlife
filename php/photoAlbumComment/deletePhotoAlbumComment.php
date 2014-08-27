<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
	
	if (isset ($_POST['idLoad']))   { $idLoad=$_POST['idLoad'];}
	if (isset ($_POST['idCommentAlbum']))   { $idCommentAlbum=$_POST['idCommentAlbum'];}
	if (isset ($_POST['time']))   { $time=$_POST['time'];}
	$idAuth = $ProfileAuth->ID;

	$time = date("j.n.Y H:i:s", $time);
	print_r($time);exit();
	// print_r($idLoad);
	// print_r($idCommentAlbum);
	// print_r($idAuth);
	// exit();
	$idAuthorComment = $DB->getOne(QS::$getProfileId_2, $idLoad, $idCommentAlbum);
	$extension = $DB->getOne(QS::$getExtansion, $idLoad, $idCommentAlbum);

	if($idLoad == $idAuth && !$extension || $idAuth == $idAuthorComment && !$extension)
		// $status = true;
	{
		$DB->query(QS::$deletePhotoAlbumComment, $idLoad, $idLoad, $idCommentAlbum);
		$result = "done";
	}
	else
		$result = "error";

	print json_encode($result);
?>