<?php
require_once '.htpaths';
require_once PROJECT_PATH."/auth/y.php";
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/****** CONNECT TO DB_SOCIAL ******/ require PROJECT_PATH.'/include/dbsocial.php';

if(isset($_POST['registe_sbt']))
{    
    if (isset ($_POST['login']))    { $login=$_POST['login']; } 
    if (isset ($_POST['pswd']))     { $pswd=$_POST['pswd']; }
    if (isset ($_POST['email']))    { $email=$_POST['email']; } 
    if (isset ($_POST['firstName']))     { $firstName=$_POST['firstName']; }
    if (isset ($_POST['lastName']))    { $lastName=$_POST['lastName']; }
    if (isset ($_POST['middleName']))    { $middleName=$_POST['middleName']; }  

    ProfileAuth::Registration($login, $pswd, $email, $firstName, $lastName, $middleName, true, $DB);
}
?>