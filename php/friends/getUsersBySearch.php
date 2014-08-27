<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

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
                    $FI = $profileUser->FI();
                    
                    $relation = $DB->getOne(QS::$qfriend1, $idAuth, $idUser);
                    switch($relation){
                        case null:
                            $classModifier = "sendAddRequest";
                            break;
                        case 0:
                            $classModifier = "editFriendRequest";
                            break;
                        case 1:
                            $classModifier = "editAddRequest";
                            break;
                        case 2:
                            $classModifier = "acceptFriendRequest";
                            break;
                        case 3:
                            $classModifier = "acceptFriendRequestOld";
                            break;
                    }
                    
                    # Укорачиваем фамилию, имя
                    if(mb_strlen($FI) > 21)
                        $FI = mb_substr($FI, 0, 21)."...";
                    $users[$idUser] = array(
                        'id' => $idUser,
                        'fi' => $FI,
                        'pathAvatar' => $profileUser->ProfilePathAvatar(),
                        'classMod' => $classModifier
                    );
                }
            }
            else
                $users = "Empty";
    }
print json_encode($users);
?>
