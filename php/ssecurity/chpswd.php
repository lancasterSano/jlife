<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['pswdo']))   { $pswdo=$_POST['pswdo'];}
if (isset ($_POST['pswdn']))   { $pswdn=$_POST['pswdn'];}
if (isset ($_POST['pswdns']))   { $pswdns=$_POST['pswdns'];}
$errors = array(); $res = array();
if(isset ($pswdo) && isset ($pswdn) && isset ($pswdns))
{
    if(mb_strlen($pswdo)){
        if(validateTitleLength($pswdo, 6, 32)){
            if(!validatePSWD($pswdo)) $errors['indv']['valid_pswdo_characters']="0";
        } else $errors['indv']['valid_pswdo_length']="0";
    } else $errors['indv']['valid_pswdo_empty']="0";
    if(mb_strlen($pswdn)){
        if(validateTitleLength($pswdn, 6, 32)){
            if(!validatePSWD($pswdn)) $errors['indv']['valid_pswdn_characters']="0";
        } else $errors['indv']['valid_pswdn_length']="0";
    } else $errors['indv']['valid_pswdn_empty']="0";

    if(!count($errors['indv'])){
        if(mb_strlen($pswdns)){
            if(validateTitleLength($pswdns, 6, 32)){
                if(!validatePSWD($pswdns)) $errors['indv']['valid_pswdns_characters']="0";
            } else $errors['indv']['valid_pswdns_length']="0";
        } else $errors['indv']['valid_pswdns_empty']="0";
        
        if(!count($errors['indv'])){
            if(!($pswdn === $pswdns)) $errors['indv']['valid_pswdn_pswdns_ne']="0";
            else { // старый пароль
                if($UA->validCurPSWD($pswdo)) {
                    // новый пароль
                    if($UA->validCurPSWD($pswdn)) $errors['indv']['valid_pswdcopy']="0";
                    if(!count($errors['indv'])){
                        $chnges = $UA->getChangePSWD($pswdn);
                        if($chnges==null) {
                            $pswdn = md5_jlife($pswdn);
                            $result = $UA->changePSWD($pswdn);
                            $data['chem'] = array('o'=>$result['o']);
                        } else $errors['indv']['valid_pswd_usedearlier']=array('o'=>0,'ochpswdd'=>$chnges['datevalid']);
                    }
                } else $errors['indv']['valid_pswd_fail']="0";
            }
        }
    }
} else $errors['indn'] = 0;
print json_encode(rajax($data, $errors));
?>