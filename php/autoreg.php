<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/JLIFE_MAIL.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

$errors = array(); $res = array();
if(isset($_POST['firstname']) 
	&& isset($_POST['lastname']) 
	&& isset($_POST['middlename']) 
	&& isset($_POST['login']) 
	&& isset($_POST['pswd']) 
	&& isset($_POST['email'])) {

	$login=$_POST['login'];
	$pswd=$_POST['pswd'];
	$email=$_POST['email'];
	$firstName=$_POST['firstname'];
	$lastName=$_POST['lastname'];
	$middleName=$_POST['middlename'];
    /****** AuthentificateProfile ******/
    // $UA = new ProfileAuth($DB);
    $idp = ProfileAuth::Registration($login, $pswd, $email, $firstName, $lastName, $middleName, false, $DB);
    if(!$idp) $errors['reg'] = 0;
    $data['successreg'] = array("idprofile"=>$idp);
}
else {
	$errors['post'] = 1;
}
print json_encode(rajax($data, $errors));
?>