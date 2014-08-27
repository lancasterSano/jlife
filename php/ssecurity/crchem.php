<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/JLIFE_MAIL.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['em']))   { $em=$_POST['em'];}
if (isset ($_POST['pswd']))   { $pswd=$_POST['pswd'];}
if (isset ($_POST['hex']))   { $hex=$_POST['hex'];}
$errors = array(); $res = array();
if(isset ($hex) &&  !empty ($hex)) {
    $rez = $UA->repeatSendChangeRequestLogin($hex);
    if(!$rez) {
        $errors['indv']['hex']="0";
    }
    else if(is_array($res)) {
        $request = $UA->getValidCreateChangeRequestEmail();

        // отправить письмо.
        $ProfileAuth = $UA->getProfile();
        $smarty->assign('ProfileAuth', $ProfileAuth);
        $smarty->assign('hex', $hex);
        $smarty->assign('JLIFE_DOMEN', $_SERVER['HTTP_HOST']);

        $to = $request['newdata'];
        $subject = 'Подтверждение электронной почты' ;
        $body = $smarty->fetch('./general/mail-accept-email.tpl');
        $MAILJLIFE = new JLIFE_MAIL($to, $subject, $body, null);
        $MAILJLIFE->send();

        $data['rrchem'] = array('o'=>1,'nem'=>$request['newdata'],'dsr'=>setRussianDateFromMysql($request['datecreate']),'hex'=>$request['hex']);
    }
    else $data['rrchem'] = array('o'=>0);
}
else {
    if(isset ($em) && isset ($pswd))
    {
        if(mb_strlen($em)) {
            if(!validateEmail($em)) $errors['indv']['valid_email_fail']="0";
        } else $errors['indv']['valid_email_empty']="0";
        
        if(mb_strlen($pswd)){
            if(validateTitleLength($pswd, 6, 32)){
                if(!validatePSWD($pswd)) $errors['indv']['valid_pswd_characters']="0";
            } else $errors['indv']['valid_pswd_length']="0";
        } else $errors['indv']['valid_pswd_empty']="0";
        
        if(!count($errors['indv'])){
            $pswd = md5_jlife($pswd);
            if($UA->validCurPSWD($pswd)) {
                if($ProfileAuth->email == $em) $errors['indv']['emcopy']="0";
            } else $errors['indv']['valid_pswd_fail']="0";

            if(!count($errors['indv'])){
                $count = $DB->getOne(QS::$q0, $em);
                if($count==0) {
                    $datecreate = date("Y-m-d H:i:s");
                    $datevalid = new DateTime($datecreate);
                    $datevalid->modify('+1 day');
                    $datevalid = date_format($datevalid, "Y-m-d H:i:s");
                    $hex = md5($ProfileAuth->ID."11".$em.$datecreate.$datevalid);

                    $changeprivatedata = $UA->sendChangeRequestEmail($datecreate, $em, $datevalid, $hex);
                    if($changeprivatedata) {
                        
                        // отправить письмо.
                        $ProfileAuth = $UA->getProfile();
                        $smarty->assign('ProfileAuth', $ProfileAuth);
                        $smarty->assign('hex', $hex);
                        $smarty->assign('JLIFE_DOMEN', $_SERVER['HTTP_HOST']);

                        $to = $em;
                        $subject = 'Подтверждение электронной почты' ;
                        $body = $smarty->fetch('./general/mail-accept-email.tpl');
                        $MAILJLIFE = new JLIFE_MAIL($to, $subject, $body, null);
                        $MAILJLIFE->send();

                        $data['chem'] = array('o'=>converterBoolNumber($changeprivatedata),'nem'=>$em,'dsr'=>setRussianDateFromMysql($datecreate),'hex'=>$hex);
                    }
                    else $data['chem'] = array('o'=>converterBoolNumber($changeprivatedata));
                } else $errors['indv']['embusy']="0";
            }
        }
    } else $errors['indn'] = 0;
}
print json_encode(rajax($data, $errors));
?>