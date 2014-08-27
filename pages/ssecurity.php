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
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "ssecurity");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);
$smarty->debugging = false;
// validate hex
$hexverify = $_GET['asec'];

$scceptsecurity = isset($hexverify) ? true : false;
$notices = array();
// ###### SECURITY ######
if(!$scceptsecurity) {
	$smarty->assign('numTab', 2);
	if(isset($_GET["msc"]) && isset($_SESSION["INFO_MAIL_CHANGED"])) { 
		unset($_SESSION["INFO_MAIL_CHANGED"]);
		$notices['INFO_MAIL_CHANGED'] = array('type' =>1, 'messages' => array() );
		$notices['INFO_MAIL_CHANGED']['messages'][] = array('title' => preg_replace("/%MAIL/", $ProfileAuth->hiddenEmail(), MS_CREATE_MAIL_SUCCESS_TITLE));
	}
	if(!$ProfileAuth->private) {
		$request_create_login = $UA->getValidCreateChangeRequestEmail();
		if($request_create_login != null) {
			$notices['INFO_SEND_MAIL_CREATEMAIL'] = array('type' =>1, 'messages' => array() );
			$notices['INFO_SEND_MAIL_CREATEMAIL']['messages'][] = array(
						'title' => preg_replace("/%MAIL/", 
							hiddenEmail($request_create_login['newdata']), preg_replace("/%HEX/", $request_create_login['hex'], MI_REQUEST_CREATE_MAIL_TITLE)),
						'text' => preg_replace("/%DATE_SEND/", setRussianDateFromMysql($request_create_login['datecreate']), MI_REQUEST_CREATE_MAIL_TEXT)
					);
		}
		$notices['INFO_CHANE_PLEASE_MAIL'] = array('type' =>1, 'messages' => array() );
		$notices['INFO_CHANE_PLEASE_MAIL']['messages'][] = array('title' => MI_INFO_CHANE_PLEASE_MAIL_TITLE, 'text' => MI_INFO_CHANE_PLEASE_MAIL_TEXT );
	}
	else {
		$request_change_login = $UA->getValidCreateChangeRequestEmail();
		if($request_change_login != null) {
			$notices['INFO_SEND_MAIL_CHMAIL'] = array('type' =>1, 'messages' => array() );
			$notices['INFO_SEND_MAIL_CHMAIL']['messages'][] = array(
						'title' => preg_replace("/%MAIL/", hiddenEmail($request_change_login['newdata']), 
							preg_replace("/%HEX/", $request_change_login['hex'], MI_REQUEST_CHANGE_MAIL_TITLE)),
						'text' => preg_replace("/%DATE_SEND/", setRussianDateFromMysql($request_change_login['datecreate']), MI_REQUEST_CHANGE_MAIL_TEXT)
					);
		}
	}
}
// ###### ACCEPT SECURITY ######
else { // $acceptsecurity = false;
	$smarty->assign('numTab', 3);
	$request = $UA->getValidCreateChangeRequestEmailByHex($hexverify);
	if($request==NULL) {
		$requestSomebody = $UA->getValidCreateChangeRequestEmailByHexSomebody($hexverify);
		if($requestSomebody != NULL) {
			if($requestSomebody['idprofile'] != $ProfileAuth->ID ) {
				$view = 'logout';
				// $UA->logout('../pages/ssecurity.php?asec='.$hexverify);
				$UA->logout('../pages/index.php');
			}
		}
		$view = 'error';
	}
	else $view = 'crchem'; 
}

$notices['INFO_CHANGE_PSWD'] = array('type' =>1, 'messages' => array() );
$notices['INFO_CHANGE_PSWD']['messages'][] = array(
			'title' => MI_CHANGE_PSWD_TITLE
			// ,
			// 'text' => MI_REQUEST_CHANGE_MAIL_TEXT
		);
$smarty->assign('notices', $notices);
$smarty->assign('hexverify', $hexverify);
$smarty->assign('scceptsecurity', $scceptsecurity);

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

$smarty->assign('PageTitle', 'Настройки личных данных '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/USMF/inc_ssecurity.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionSettingsPersonalizationSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);


if(!$scceptsecurity) $wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/ssecurity.tpl");
else {
	switch ($view) {
		case 'crchem':
			$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/acceptsem.tpl");
			break;
		default:
			$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/acceptse.tpl");
			break;
	}
}
$smarty->assign('block_mainField', $wallMainField_tpl);

$smarty->display("pageUSMF.tpl");
	// $errors['ind'] = 0;
	// $debug_context[SETTING_debug]['request_create_login'] = rajax($rez, $errors);
	// $notices = 'ОШИБКА ПРОСТО СТРОКА';
	// $notices = array('ОШИБКА CHERRY', 'ОШИБКА KOLYA');
	// $notices = array('type' => 4, 'messages' => 56 );
	// $notices = array('type' => 4, 'messages' => "ОШИБКА ОШИБКА ОШИБКА" );
	// $notices = array('type' => 4, 'messages' => array('ПЕРВАЯ')); 
	// $notices = array('type' => 4, 'messages' => array('ПЕРВАЯ', 'ПЕРВАЯ')); 
	// $notices = array('type' => 4, 'messages' => array(0 => 'ПЕРВАЯ', 1 => 'ВТОРАЯ', 2 =>'ТРЕТЬЯ3') );
	// $notices = array('type' => 4, 'messages' => array(0 => array('title'=>'ПЕРВАЯ'), 1 => array('title'=>'ВТОРАЯ'), 2 => array('title'=>'ТРЕТЬЯ') ) ); +
	// $notices = array(
	// 	999 => array('type' => 4, 'messages' => array(0 => array('title'=>'ПЕРВАЯ'), 1 => array('title'=>'ВТОРАЯ'), 2 => array('title'=>'999') ) ),
	// 	array('type' => 2, 'messages' => array(0 => array('title'=>'ПЕРВАЯ'), 1 => array('title'=>'ВТОРАЯ'), 2 => array('title'=>'777') ) )
	// );
	// $notices = array(
	// 	// 999 => "строка",
	// 	777 => array('type' =>1, 'messages' => array(0 => array('title'=>'ПЕРВАЯ'), 1 => array('title'=>'ВТОРАЯ'), 2 => array('title'=>'&&&') ) ),
	// 	999 => array('type' => 4, 'messages' => array(0 => array('title'=>'ПЕРВАЯ'), 1 => array('title'=>'ВТОРАЯ'), 2 => array('title'=>'999') ) )
	// );
?>