<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "material");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



//if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else 
$ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
$p1 = $DB->getRow(QS::$q2, $ProfileID);
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Материал преподавателя'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);


while ( $i<= 10) {
	$i++;
	$material[$i] = array(
	    "id" => "1",
	    "level" => "6",
	    "paragraph" => "30",
	    "paragraphDone" => "12",
	    "section" => "1",
	    "deleted" => "1",
	);
};

var_dump($material);


$smarty->assign('material',$material);


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$smarty->assign('numTab', 1);
$smarty->assign('activeTab',21);

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_prepod.tpl");
$smarty->assign('block_include_head', $block_include_head);

$topUnionlim_tpl = $smarty->fetch("./mainContent/do/union/topUnion_prepod.tpl");
$smarty->assign('block_union', $topUnionlim_tpl);

$prepodMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/material.tpl");
$smarty->assign('block_mainField', $prepodMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>