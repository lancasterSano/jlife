<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "goduser");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$smarty->assign('ProfileLoad', $ProfileLoad);



$ProfileID = $ProfileAuth->ID;
$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'Профиль отсутствует');
$smarty->assign('ProfileLoad', $ProfileLoad);

/************* MAIN_CONTENT *************/
require_once(PROJECT_PATH."/pages/page_part/smarty_incut.php");
$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 12);

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_development.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnion_goduser.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/goduser.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>