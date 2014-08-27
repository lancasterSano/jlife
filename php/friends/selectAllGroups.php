<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if (isset ($_POST['l']))   { $idProfileLoad=$_POST['l'];}
if(isset ($idProfileLoad)) {
    //группы владельца страницы
    $groupsFromDB = $DB->getAll(QS::$qfriend3, $idProfileLoad);
    //друзья владельца страницы
    $contacts = array();
    //Цикл по списку групп
    foreach($groupsFromDB as $groupFromDB) {
        $friendsFromDB = $DB->getAll(QS::$qfriend4, $idProfileLoad, $idProfileLoad, $groupFromDB["id"], SETTING_COUNT_FIRST_LOAD_FRIENDS);
        //Цикл по списку людей в группе
        foreach($friendsFromDB as $friendFromDB) {
            $profileFriendFromDb = $DB->getRow(QS::$q3, $friendFromDB["id"]);
            $profileFriend = new Profile($friendFromDB["id"], $profileFriendFromDb);
            $friend = array(
                "id" => $profileFriend->ID,
                "FI" => $profileFriend->FI(),
                "avaPath" => $profileFriend->ProfilePathAvatar(),
            );
            $friends[] = $friend;
            unset($friend);
        }
        $group = array(
            "id" => $groupFromDB["id"],
            "name" => $groupFromDB["name"],
            "countuser" => $groupFromDB["countuser"],
            "friends" => $friends
        );
        unset($friends);
        $contacts["id".$group["id"]] = $group;
        unset($group);
    }
    $res = $contacts;
} 
else 
{
      $res = null;
}
print json_encode($res);
?>