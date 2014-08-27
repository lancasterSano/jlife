<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

##############################################

if (isset ($_POST['idLearner']))   { $idLearner=$_POST['idLearner'];}
if (isset ($_POST['idLesson']))   { $idLesson=$_POST['idLesson'];}
if (isset ($_POST['idLessonType']))   { $idLessonType=$_POST['idLessonType'];}
if (isset ($_POST['mark']))   { $mark=$_POST['mark'];}
if (isset ($_POST['idSubgroup']))   { $idSubgroup=$_POST['idSubgroup'];}
if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}

		# Определяем порог при добавлении оценки к обшему баллу для данной школы
		$step = $DB_DO->getOne(QSDO::$getStepSchool, $idSchool);

		$DB_DO->query(QSDO::$deleteMark, $idLessonType, $idLearner);
		
		if($mark == 13)
			$DB_DO->query(QSDO::$substrCountAbsent, $idSubgroup, $idLearner);
		else
		{
			// $DB_DO->query(QSDO::$substrCountMarkSumMark, $mark, $idSubgroup, $idLearner);
			if($mark >= $step)
				$DB_DO->query(QSDO::$substrRating, $mark, $idLearner, $idSubgroup);
			
			$DB_DO->query("CALL rSchoolmark(?i,?s,?s,?i)", $idLessonType, $idLearner, $idSubgroup, $mark);

			// $DB_DO->query(QSDO::$updateAverageMark, $idSubgroup, $idLearner);

		}	
		$averageResult['results'] = $DB_DO->getAll(QSDO::$getAverageResult, $idSubgroup, $idLearner);
		$averageResult['results'][0]['averagemark'] = round($averageResult['results'][0]['averagemark'],1);
print json_encode($averageResult);
?>