<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

##############################################
// if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}
// if (isset ($_POST['idClass']))   { $idClass=$_POST['idClass'];}
if (isset ($_POST['searchKey']))   { $searchKey=$_POST['searchKey'];}
if (isset ($_POST['startId']))   { $startId=$_POST['startId'];}

    if(isset($searchKey) && isset($startId))
    {
        $searchKey = normalize(htmlspecialchars($searchKey))."%";
        if($startId == 0)
            $idUser = $DB->getAll(QS::$searchUsersIdFromProfile, $searchKey, $searchKey);
        else
            $idUser = $DB->getAll(QS::$getMoreSearchUsersIdFromProfile, $searchKey, $searchKey, $startId);
        
        if($idUser)
     	{
     		foreach($idUser as $key => $value)
     		{	
     			$idUser = $value['id'];
     			$userInfo = $DB->getRow(QS::$q3, $idUser);
            	$profileUser = new Profile($idUser, $userInfo);
     			$users[$idUser] = array(
     							'id' => $idUser,
     							'fi' => $profileUser->FI(),
     							'pathAvatar' => $profileUser->ProfilePathAvatar()
     							);
     		}
     	}
        else
            $users = "Empty";
    }


print json_encode($users);
?>