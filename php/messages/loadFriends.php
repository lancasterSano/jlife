<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['me']))   { $myID=$_POST['me'];}
if (isset ($_POST['currentRecepient']))   { $idRecepient=$_POST['currentRecepient'];}
if (isset ($myID) && !empty ($myID)) {
    $friends = $DB->getAll(QS::$getAllFriends, $myID);
    if($friends) {
        //i have at least one friend
        $response_array = array();
        $friendIDs = array();
        foreach($friends as $friend) {
            $id = $friend["id"];
            $profileFriend = new Profile($id, $friend);
            $friendFI = $profileFriend->FI();
            
            $profileAvatarPath = $profileFriend->ProfilePathAvatar();
            $response_array[] = array("id" => $id,
                                      "fio" => $friendFI,
                                      "avatarPath" => $profileAvatarPath);
            $friendIDs[] = $id;
        }
        if(isset($idRecepient) && $idRecepient) {
            $recepientFromDb = $DB->getRow(QS::$q3, $idRecepient);
            $profileRecepient = new Profile($idRecepient, $recepientFromDb);
            $flRecepient = $profileRecepient->FI();
            $avatarPath = $profileRecepient->ProfilePathAvatar();
            $recepientProfile = array("id" => $idRecepient, "fio" => $flRecepient, "avatarPath" => $avatarPath);
            if(!in_array($idRecepient, $friendIDs)) {
                $response = array("status" => "loadedFrAndRec", "friends" => $response_array, "recepient" => $recepientProfile);
            } else {
                $i = 0;
                foreach($friends as $friend){
                    if($friend["id"] == $idRecepient) {
                        $index = $i;
                    }
                    $i++;
                }
                unset($response_array[$index]);
                array_unshift($response_array, $recepientProfile);
                $response = array("status" => "loadedFrWithRec", "friends" => $response_array, "recepient" => null);
            }
        } else {
            $response = array("status" => "loadedFr", "friends" => $response_array, "recepient" => null);
        }
    } else {
        if(isset($idRecepient) && $idRecepient) {
            $recepientFromDb = $DB->getRow(QS::$q3, $idRecepient);
            $profileRecepient = new Profile($idRecepient, $recepientFromDb);
            $flRecepient = $profileRecepient->FI();
            $avatarPath = $profileRecepient->ProfilePathAvatar();
            $recepientProfile = array("id" => $idRecepient, "fio" => $flRecepient, "avatarPath" => $avatarPath);
            $response = array("status" => "loadedRec", "friends" => null, "recepient" => $recepientProfile);
        } else {
            $response = array("status" => "loadedNoFrAndRec", "friends" => null, "recepient" => null);
        }
    }
} 
print json_encode($response);
?>

