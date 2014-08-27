<?php 
# Version 2.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "spersonalization");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$ProfileLoad = $ProfileAuth;

// Название вкладки "Школа №51"
	$LearnerRoles = $ProfileLoad->getRolesByRole(ROLES::$Learner);
	// if(!count($LearnerRoles)) { header("Location: ".PROJECT_PATH."/pages/403.php"); }

	if(count($LearnerRoles)){
		$ridSchoolAdress = array(); $ridSchool = array();

		foreach ($LearnerRoles as $LearnerRole) 
		{
		    $ridSchoolAdress[$LearnerRole["idschool"]] = $LearnerRole["idadress"];
		    $ridSchool[] = $LearnerRole["idschool"];
		}
		// Все школы где учится ученик
		$sch = $DB_DO->getAll(QSDO::$getSchoolsArray, $ridSchool);
		foreach ($sch as $key => $value) {
			$Schools[$value["id"]] = $value;
		}
		$curIDSchool = $ridSchool[0];
		// if($Schools[$curIDSchool] == NULL) { header("Location: ".PROJECT_PATH."/pages/403.php"); }
		
		$Learner = new Learner($ridSchoolAdress[$curIDSchool]);
	    $smarty->assign('LearnerClass', $Learner->getClass());
		$smarty->assign('Schools', $Schools);
	}


$smarty->assign('PageTitle', 'Настройки аватарки'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('numTab', 1);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_spersonalization.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionSettingsPersonalizationSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);


$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/spersonalization.tpl");
$smarty->assign('block_mainField', $wallMainField_tpl);

$smarty->display("pageUSMF.tpl");
?>