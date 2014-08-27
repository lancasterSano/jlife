<?php 


# Version 2.0
// require_once '.htpaths';
// require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
// require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
// require_once(PROJECT_PATH."/php/common.php");
// require_once(PROJECT_PATH."/php/class/QueryStorage.php");
// require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
// $smarty->assign('block_page_sys', "development");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();
$ProfileLoad = $ProfileAuth;
$ProfileID = $ProfileAuth->ID;
$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$smarty->assign('ProfileLoad', $ProfileLoad);

/************* MAIN_CONTENT *************/
// if($smarty->tpl_vars['is_do']->value == false) require_once("./page_part/smarty_incut.php"); else require_once("./../page_part/smarty_incut.php");
// if($smarty->tpl_vars['is_do']->value == false) echo 'page is_do = '.$smarty->tpl_vars['is_do']->value.' Рома замени.';
// require_once("./../page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_development.tpl"); $smarty->assign('block_include_head', $block_include_head);

$wallMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/development.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
try {
	$smarty->display("pageUSMF.tpl");	
} catch (Exception $e) {
var_dump($e);exit();	
}
?>