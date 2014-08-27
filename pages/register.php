<?php 
# Version 2.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "register");
// $smarty->assign('block_css', "style_authorize");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$ProfileAuth = $UA->getProfile();
$smarty->assign('ProfileAuth', $ProfileAuth);
$ProfileLoad = $ProfileAuth;

$smarty->assign('ProfileLoad', $ProfileLoad);
$smarty->debugging = false;

if(isset($_GET["er"])) $regError = $_GET["er"]==0 ? false:true;
else $regError = null;

$smarty->assign('regError', $regError);

if (isset ($_GET['l']))  { $loginV=$_GET['l']; }
if (isset ($_GET['p']))  { $pswdV=$_GET['p']; }
if (isset ($_GET['e']))  { $emailV=$_GET['e']; }
if (isset ($_GET['f']))  { $firstNameV=$_GET['f']; }
if (isset ($_GET['s']))  { $lastNameV=$_GET['s']; }
if (isset ($_GET['m']))  { $middleNameV=$_GET['m']; }

$smarty->assign('login', isset($loginV)? $loginV : "");
$smarty->assign('pswd', isset($pswdV)? $pswdV : "");
$smarty->assign('email', isset($emailV)? $emailV : "");
$smarty->assign('firstName', isset($firstNameV)? $firstNameV : "");
$smarty->assign('lastName', isset($lastNameV)? $lastNameV : "");
$smarty->assign('middleName', isset($middleNameV)? $middleNameV : "");

$smarty->assign('PageTitle', 'Регистрация профиля');
$smarty->assign('numTab', 11);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_development.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnion_goduser.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/union/topUnion_reg.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);





// $block_include_head = $smarty->fetch("./include_head/USMF/inc_ssecurity.tpl"); $smarty->assign('block_include_head', $block_include_head);

// $topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionSettingsPersonalizationSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
// $topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);

// $RegTopUnion_tpl = $smarty->fetch("./mainContent/union/topUnion_reg.tpl");
// $smarty->assign('block_mainField', $RegTopUnion_tpl);

$smarty->display("pageUSMF.tpl");
?>