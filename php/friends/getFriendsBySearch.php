<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

if(isset($_POST["idGroup"])){ $idGroup = $_POST["idGroup"];}
if(isset($_POST["idLoad"])){ $idLoad = $_POST["idLoad"];}
if(isset($_POST["searchKey"])) {$searchKey = $_POST["searchKey"];}

if(isset($idGroup) && isset($idLoad) && isset($searchKey)) {
    $searchKey = $searchKey."%";
    if($idAuth == $idLoad){
        if($idGroup){
            $nameGroup = $DB->getOne(QS::$getGroupById, $idLoad, $idGroup);
            $friendsInGroupFromDB = $DB->getAll(QS::$getFriendsByGroupLike, $idLoad, $idLoad, $idGroup, $searchKey, $searchKey);
            if($friendsInGroupFromDB){
                foreach ($friendsInGroupFromDB as $friendInGroup) {
                    $idFriend = $friendInGroup["id"];
                    $friendInGroupFromDB = $DB->getRow(QS::$q3, $idFriend);
                    $profileFriend = new Profile($idFriend, $friendInGroupFromDB);
                    $fiFriend = $profileFriend->FI();
                    $avaPathFriend = $profileFriend->ProfilePathAvatar();
                    $friends[] = array(
                        "id" => $idFriend,
                        "FI" => $fiFriend,
                        "avaPath" => $avaPathFriend
                    );
                }
                $group[] = array(
                        "id" => $idGroup,
                        "name" => $nameGroup,
                        "friends" => $friends
                    );
                $response = array("status" => "frInOneGroup", "res_array" => $group);
            } else {
                $response = array("status" => "nofrInOneGroup", "res_array" => null);
            }
        }
        else {
            $groupsOfFriends = $DB->getAll(QS::$getGroupsOfSearchedFriends, $idLoad, $idLoad, $idLoad, $idLoad, $searchKey, $searchKey);
            if($groupsOfFriends){
                foreach ($groupsOfFriends as $groupOfFriend) {
                    $idGroup = $groupOfFriend["id"];
                    $nameGroup = $groupOfFriend["name"];
                    $friendsAllSearched = $DB->getAll(QS::$getFriendsByGroupLike, $idLoad, $idLoad, $idGroup, $searchKey, $searchKey);
//                    $friendsInGroupFromDb = $DB->getAll(QS::$getFriendsByGroupLikeLimit, $idLoad, $idLoad, $idGroup, $searchKey, $searchKey, SETTING_COUNT_FIRST_LOAD_FRIENDS);
                    foreach($friendsAllSearched as $friendInGroup){
                        $idFriend = $friendInGroup["id"];
                        $friendInGroupFromDB = $DB->getRow(QS::$q3, $idFriend);
                        $profileFriend = new Profile($idFriend, $friendInGroupFromDB);
                        $fiFriend = $profileFriend->FI();
                        $avaPathFriend = $profileFriend->ProfilePathAvatar();
                        $friends[] = array(
                            "id" => $idFriend,
                            "FI" => $fiFriend,
                            "avaPath" => $avaPathFriend
                        );
                    }
                    $groups[] = array(
                        "id" => $idGroup,
                        "name" => $nameGroup,
                        "countuser" => count($friendsAllSearched),
                        "friends" => $friends
                    );
                    unset($friends);
                }
                $response = array("status" => "frInAllGroups", "res_array" => $groups);
            } else {
                $response = array("status" => "nofrInAllGroups", "res_array" => null);
            }
        }
    } else {
        $friendsWithoutGroupFromDB = $DB->getAll(QS::$getAllSearchedFriendsOfUser, $idLoad, $searchKey, $searchKey);
        if($friendsWithoutGroupFromDB){
            foreach ($friendsWithoutGroupFromDB as $friendWithoutGroup) {
                $idFriend = $friendWithoutGroup["id"];
                $friendWithoutGroupFromDB = $DB->getRow(QS::$q3, $idFriend);
                $profileFriend = new Profile($idFriend, $friendWithoutGroupFromDB);
                $fiFriend = $profileFriend->FI();
                $avaPathFriend = $profileFriend->ProfilePathAvatar();
                $friends[] = array(
                    "id" => $idFriend,
                    "FI" => $fiFriend,
                    "avaPath" => $avaPathFriend
                );
            }
            $response = array("status" => "frWithoutGroups", "res_array" => $friends);
        } else {
            $response = array("status" => "nofrWithoutGroups", "res_array" => null);
        }
    }    
}
else {
    $response = array("fail", null);
}
print json_encode($response);
?>
