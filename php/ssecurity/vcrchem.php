<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['pswd']))   { $pswd=$_POST['pswd'];}
if (isset ($_POST['hex']))   { $hex=$_POST['hex'];}
$errors = array(); $res = array();

if(isset ($hex) && isset ($pswd))
{
    if(mb_strlen($pswd)){
        $pswdlegth = validateTitleLength($pswd, 6, 32);
        if($pswdlegth) {
            $pswdchar = validatePSWD($pswd);
            if($pswdchar) {
                $pswd = md5_jlife($pswd);
                if($UA->validCurPSWD($pswd)) {
                    if($UA->getValidCreateChangeRequestEmailByHex($hex) != NULL) {
                        $changeEmail = $UA->changeEmail($hex);
                        if(isset($changeEmail['o']) && $changeEmail['o']) {
                            $data['chem'] = array('o'=>$changeEmail['o'],'nem'=>$changeEmail['nem']);
                        }
                        else $data['chem'] = array('o'=>0);
                    } else $errors['indv']['hex']="0";
                } else $errors['indv']['valid_pswd_fail']="0";
            } else $errors['indv']['valid_pswd_characters']="0";
        } else $errors['indv']['valid_pswd_length']="0";
    } else $errors['indv']['valid_pswd_empty']="0";
} else $errors['indn']="0";

print json_encode(rajax($data, $errors));
?>