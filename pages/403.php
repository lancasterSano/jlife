<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "403");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();
$idAuth = $ProfileAuth->ID;

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$smarty->assign('ProfileLoad', $ProfileLoad);



$ProfileID = $ProfileAuth->ID;
$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'Недостаточно прав доступа');
$smarty->assign('ProfileLoad', $ProfileLoad);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");

// $block_include_head = $smarty->fetch("./include_head/USMF/inc_absent.tpl"); $smarty->assign('block_include_head', $block_include_head);

// // union-а нет

// $wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/absent.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
// /************ запускаем показ шаблона smarty *************/
// $smarty->display("pageUSMF.tpl");
//

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_development.tpl");
$smarty->assign('block_include_head', $block_include_head);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/403.tpl");
$smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>