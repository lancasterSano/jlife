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
if (isset ($_POST['mark']))   { $newMark=$_POST['mark'];}
if (isset ($_POST['idSubgroup']))   { $idSubgroup=$_POST['idSubgroup'];}
if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}
	# Определяем порог при добавлении оценки к обшему баллу для данной школы
	$step = $DB_DO->getOne(QSDO::$getStepSchool, $idSchool);
	# Проверяем, поставлена ли уже оценка
	$oldMark = $DB_DO->getAll(QSDO::$checkIssetMarkInTable, $idLessonType, $idLearner);
	# Если оценка поставлена
	if($oldMark[0]['id'])
	{
		if($newMark != 13)
		{
			if($oldMark[0]['value'] != null)
				# Если старая оценка >= границы, то отнимаем эту оценку от суммы баллов
				if($oldMark[0]['value'] >= $step)
					$DB_DO->query(QSDO::$substrRating, $oldMark[0]['value'], $idLearner, $idSubgroup);

				# Если новая оценка >= границы, то прибавляем эту оценку к сумме баллов
				if($newMark >= $step)
					$DB_DO->query(QSDO::$summRating, $newMark, $idLearner, $idSubgroup);

				# Обновляем оценку
				$DB_DO->query(QSDO::$updateMark, $newMark, $idLessonType, $idLearner);

			if($oldMark[0]['value'] == null)
			{
				# Обновляем оценку
				$DB_DO->query(QSDO::$updateMarkOnAbsentDelete, null, $idLessonType, $idLearner);
				# Инкриментируем пропуск
				$DB_DO->query(QSDO::$updateCountAbsentSubstr, $idLearner, $idSubgroup);
			}
		}
		else
		{
			if($oldMark[0]['value'] != null)
			{
				# Если старая оценка >= границы, то отнимаем эту оценку от суммы баллов
				if($oldMark[0]['value'] >= $step)
					$DB_DO->query(QSDO::$substrRating, $oldMark[0]['value'], $idLearner, $idSubgroup);
			}

			# Обновляем оценку
			$DB_DO->query(QSDO::$updateMarkOnAbsent, null, 1, $idLessonType, $idLearner);
			if($oldMark[0]['value'] != null)
				# Инкриментируем пропуск
				$DB_DO->query(QSDO::$updateCountAbsent, $idLearner, $idSubgroup);
		}

		# Процедура удаляет последнюю оценку, декрементирует сумму оценок (-1) и пересчитывает средний балл
		$DB_DO->query("CALL rSchoolmark(?i,?s,?s,?i)", $idLessonType, $idLearner, $idSubgroup, $oldMark[0]['value']);
		
		// $DB_DO->query(QSDO::$updateMark, $newMark, $idLessonType, $idLearner); // Обновляем оценку

		# Процедура сохраняет среднюю оценку, средний балл и сумму оценок и пересчитывает
  		# эти показатели с учетом выставленной оценки 
		if($newMark != 13)
		$DB_DO->query("CALL resetVisitaverage(?i,?s,?s,?i)", $idSubgroup, $idLearner, $newMark, null);

	}
	else
	{
		if($newMark == 13)
			$DB_DO->query(QSDO::$addAbsent, $idLessonType, $idLearner, 1, $idLesson);
		else
		{
			$DB_DO->query(QSDO::$addMark, $newMark, $idLessonType, $idLearner, $idLesson); // Добавляем оценку
			if($newMark >= $step)
				$DB_DO->query(QSDO::$summRating, $newMark, $idLearner, $idSubgroup); // Суммируем к рейтингу оценку
		}
	}
	$averageResult = $DB_DO->getAll(QSDO::$getAverageResult, $idSubgroup, $idLearner);
	$averageResult[0]['averagemark'] = round($averageResult[0]['averagemark'],1);
	
    if($newMark == 0)
    	$newMark = "П";
    if($newMark == 13)
    	$newMark = "Н";
    $rez = array('mark' => $newMark, 'results' => $averageResult);
print json_encode($rez);
?>