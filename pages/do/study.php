<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH."/settings/l_const_do.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
require_once(PROJECT_PATH.'/auth/protected/components/common/NumbersPages.php');

/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
if($_GET["school"] != NULL)
{
	$find = $DB_DO->getRow(QSDO::$getSchool, $_GET["school"]);
	if($find == NULL) header("Location: ".PROJECT_PATH."/pages/404.php");
}

$ProfileLoad = $UA->getProfile();
$smarty->assign('PROJECT_PATH', PROJECT_PATH);


// Определить обладает ли доступом к обучению в роли "Ученик"
$LearnerRoles = $ProfileLoad->getRolesByRole(ROLES::$Learner);
$sysErrLearnerID = false;

// $smarty->debugging = true;
// var_dump($_COOKIE);
// подключить то что заслужил
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
	
	$curIDSchool = isset($_GET["school"]) ? $_GET["school"] : $ridSchool[0];
	if($Schools[$curIDSchool] == NULL) { header("Location: ".PROJECT_PATH."/pages/403.php"); }
	
	$Learner = new Learner($ridSchoolAdress[$curIDSchool]);
        if($Learner->idSchool != $curIDSchool) {
            $sysErrLearnerID = true;
            $notices['ME_SCHEDULE_LEARNER_ID_MISMATCH']["type"] = 2;
            $notices['ME_SCHEDULE_LEARNER_ID_MISMATCH']["messages"][] = array(
                "title" => ME_SCHEDULE_LEARNER_ID_MISMATCH_TITLE,
                "text" => ME_SCHEDULE_LEARNER_ID_MISMATCH_TEXT
            );
        }

    $headerData = array(
        "role" => "c",
        "idschool" => $Learner->idSchool,
        "idadress" => $Learner->idLearner,
        "idlearner" => null,
        "numberlearner" => null
    );
    

	$smarty->assign('Schools', $Schools);
	$smarty->assign('numTab', $curIDSchool);
	$smarty->assign('ProfileAuth', $ProfileLoad);
	$smarty->assign('ProfileLearner', $Learner);

	if(!isset($Learner->idLearner)) { echo "Warning! Learner in not set" ; exit(); }

	switch($_GET["s"]){
		case 1:
                        $headerData["pageName"] = "schedule";
                        $smarty->assign('cur_role_attr', $headerData);
			$smarty->assign('activeTab', 1);
			$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "schedule");
			$smarty->assign('PageTitle', 'Расписание '.$ProfileLoad->FI());
			require_once("./page_part/schedule.php");	break;
		case 2: 
                        $headerData["pageName"] = "subjects";
                        $smarty->assign('cur_role_attr', $headerData);
			$smarty->assign('activeTab', 2);
			$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "subjects");
			$smarty->assign('PageTitle', 'Предметы '.$ProfileLoad->FI());
			require_once("./page_part/subjects.php");	
			break;
		case 3:
		case null: 
                        $headerData["pageName"] = "journal";
                        $smarty->assign('cur_role_attr', $headerData);
			$smarty->assign('activeTab', 3);
			$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "journal");
			$smarty->assign('PageTitle', 'Журнал ' .$ProfileLoad->FI());
			require_once("./page_part/journal.php");	break;
                case 4: 
                        $headerData["pageName"] = "testresult";
                        $smarty->assign('cur_role_attr', $headerData);
			$smarty->assign('activeTab', 4);
			$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "testResult");
			$smarty->assign('PageTitle', 'Результаты '.$ProfileLoad->FI());
			require_once("./page_part/testResult.php");	
			break;
		// default: $GET["s"]; require_once("subject.php");	break;
	}
}    
else
{
    $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "development");
    $smarty->assign('PageTitle', 'Раздел находится в разработке');
	require_once(PROJECT_PATH."/pages/development.php");
}


?>