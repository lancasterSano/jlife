<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['l']))   { $idProfileLoad = $_POST['l'];}
if (isset ($_POST['g'])) { $group = $_POST['g'];}
if (isset ($_POST['m'])) { $mode = $_POST['m'];}

if(isset ($idProfileLoad) && isset ($group) && isset ($mode))
{
    if($mode == "full")
        $friendsFromDb = $DB->getAll(QS::$qfriend5, $idProfileLoad, $idProfileLoad, $group);
    else if($mode == "part")
        $friendsFromDb = $DB->getAll(QS::$qfriend4, $idProfileLoad, $idProfileLoad, $group, SETTING_COUNT_FIRST_LOAD_FRIENDS);

    //Цикл по списку людей в группе
    foreach($friendsFromDb as $friendFromDb) {
        $idFriend = $friendFromDb["id"];
        $profileFriendFromDb = $DB->getRow(QS::$q3, $idFriend);
        $profileFriend = new Profile($idFriend, $profileFriendFromDb);
        $fiFriend = $profileFriend->FI();
        $avaPathFriend = $profileFriend->ProfilePathAvatar();
        $friends[] = array(
            "id"        => $idFriend,
            "FI"        => $fiFriend,
            "avaPath"   => $avaPathFriend
        );
    }
    $response = $friends;
} 
else 
{
      $response = null;
}
print json_encode($response);
?>