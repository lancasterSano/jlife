<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

$idAuth = $ProfileAuth->ID;
if (isset ($_POST['f']))   { $idLoad =  intval($_POST['f']);}
if (isset ($_POST['g'])) { $groupString = $_POST['g'];}


if(isset ($idAuth) && isset ($idLoad) && isset ($groupString) && $idAuth != $idLoad)
{
    $relation = $DB->getOne(QS::$qfriend1, $idAuth, $idLoad);
    switch ($relation){
        case null: 
            if ($groupString) { // авторизованный пользователь подал заявку выгруженному пользователю
                           // на добавление в свои контакты (должен передать хотябы одну свою группу)
                $idgroups = preg_split('[\|]', $groupString);
                foreach ($idgroups as $idgroup) {
                    $DB->query(QS::$insertFriendToGroup, $idAuth, $idAuth, $idLoad, intval($idgroup), 1);
                }
                $DB->query(QS::$insertFriendToGroup, $idLoad, $idLoad, $idAuth, null, 2);
                $res = "editAddRequest";
            } else{ //авторизованный пользователь НЕ подал заявку выгруженному
                $res = "sendAddRequest";
            }
            break;
        case 0:
            if ($groupString) { // авторизированный пользователь изменил группы у выбранного пользователя
                $DB->query(QS::$deleteFriendFromGroups, $idAuth, $idLoad);//удаляем из всех групп выгруженного друга
                $idgroups = preg_split('[\|]', $groupString);
                foreach ($idgroups as $idgroup) { //добавляем его в выбранные группы
                    $DB->query(QS::$insertFriendToGroup, $idAuth, $idAuth, $idLoad, intval($idgroup), 0);
                }
                $res = "editFriendRequest";
            } else { //// авторизированный пользователь удалил выбранного пользователя из контактов
                $DB->query(QS::$deleteFriendFromGroups, $idAuth, $idLoad);
                $DB->query(QS::$insertFriendToGroup, $idAuth, $idAuth, $idLoad, null, 2);
                $DB->query(QS::$updateFriendMutualState, $idLoad, 1, $idAuth);
                $res = "acceptFriendRequest";
            }
            break;
        case 1:
            // авторизованный пользователь изменил поданную заявку выгруженному пользователю на добавление в свои контакты (должен передать хотябы одну свою группу)
            if ($groupString) {
                $DB->query(QS::$deleteFriendFromGroups, $idAuth, $idLoad);
                $DB->query(QS::$deleteFriendFromGroups, $idLoad, $idAuth);
                 $idgroups = preg_split('[\|]', $groupString);
                foreach ($idgroups as $idgroup) {
                    $DB->query(QS::$insertFriendToGroup, $idAuth, $idAuth, $idLoad, intval($idgroup), 1);
                }
                $DB->query(QS::$insertFriendToGroup, $idLoad, $idLoad, $idAuth, null, 2);
                $res = "editAddRequest";
            } else { //авторизованный пользователь отменил поданную заявку выгруженному
                $DB->query(QS::$deleteFriendFromGroups, $idAuth, $idLoad);
                $DB->query(QS::$deleteFriendFromGroups, $idLoad, $idAuth);
                $res = "sendAddRequest";
            }
            break;
        case 2:
            // авторизованный пользователь принял заявку от выгруженного пользователя на добавление в свои контакты (должен передать хотябы одну свою группу)
            if ($groupString) {
                $DB->query(QS::$deleteFriendFromGroups, $idAuth, $idLoad);
                $idgroups = preg_split('[\|]', $groupString);
                foreach ($idgroups as $idgroup) {
                    $DB->query(QS::$insertFriendToGroup, $idAuth, $idAuth, $idLoad, intval($idgroup), 0);
                }
                $DB->query(QS::$updateFriendMutualState, $idLoad, 0, $idAuth);
                $res = "editFriendRequest";
            }
            //авторизованный пользователь НЕ принял заявку от выгруженного пользователя
            else {
                $res = "acceptFriendRequest";
            }
            break;
        case 3:
            // авторизованный пользователь принял заявку от выгруженного пользователя на добавление в свои контакты (должен передать хотябы одну свою группу)
            if ($groupString) {
                $DB->query(QS::$deleteFriendFromGroups, $idAuth, $idLoad);
                $idgroups = preg_split('[\|]', $groupString);
                foreach ($idgroups as $idgroup) {
                    $DB->query(QS::$insertFriendToGroup, $idAuth, $idAuth, $idLoad, intval($idgroup), 0);
                }
                $DB->query(QS::$updateFriendMutualState, $idLoad, 0, $idAuth);
                $res = "editFriendRequest";
            }
            //авторизованный пользователь НЕ принял заявку от выгруженного пользователя
            else {
                $res = "acceptFriendRequestOld";
            }
            break;
    }
} 
else 
{
    $res ="error";
}
print json_encode($res);
?>