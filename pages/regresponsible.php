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
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "regresponsible");
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

$ProfileLoad = $ProfileAuth;

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

    /*RESPONSIBLES*/
    $parentLearnerlist = array(
                    'idProfile' => $ProfileLoad->ID,
                    'idLearner' => $Learner->idLearner,
                    'fio' => $Learner->LastName." ".$Learner->FirstName." ".$Learner->MiddleName,
                    'responsibles' => array()
                    );    
    $pOneLearner = $DB_DO->getAll(QSDO::$getParentsOneLearner, $Learner->idLearner);    
    if(!empty($pOneLearner))
        foreach($pOneLearner as $key => $value)
        {
        	// var_dump($p);

            $parentObject = new Responsible($value['id']);
            $parentsOneLearner[$value['id']]['id'] = $value['id'];
            $parentsOneLearner[$value['id']]['idProfile'] = $parentObject->idProfile;
        	$p = $DB->getRow(QS::$getProfileContext, $parentObject->idProfile);
            $parentsOneLearner[$value['id']]['Profile'] = new Profile($parentObject->idProfile, $p);
            $parentsOneLearner[$value['id']]['fio'] = $value['fio'];
            $parentsOneLearner[$value['id']]['relation'] = $value['relation'];
        	$p = $DB->getRow(QS::$q1_auth, $parentObject->idProfile);
            $parentsOneLearner[$value['id']]['login'] = $p['login'];
            $parentsOneLearner[$value['id']]['pswd'] = $p['password'];

            
        }
    else
        $parentsOneLearner['error'] = 'Нет родителя'; 

    $parentLearnerlist['responsibles'] = $parentsOneLearner;
    // var_dump($parentLearnerlist['responsibles']);
	$smarty->assign('parentLearnerlist', $parentLearnerlist);



    /*SPISOK RELATIONS*/
	$relations = $DB_DO->getAll(QSDO::$getRelations);


	$smarty->assign('relations', $relations);
	$smarty->assign('Schools', $Schools);
	$smarty->assign('ProfileLearner', $Learner);
	$smarty->assign('ProfileLoadLearners', $LearnerRoles);	
}

$smarty->assign('PageTitle', 'Настройки личных данных '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('numTab', 4);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_ssecurity.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionSettingsPersonalizationSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);


$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/regresponsible.tpl");

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