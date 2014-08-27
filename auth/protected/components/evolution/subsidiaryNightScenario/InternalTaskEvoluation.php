<?php
Yii::import('application.components.evolution.subsidiaryNightScenario.*');
/**
* 4.1 Internal Task Evoluation
* 4.1 Внутреннии задачи системы роста
*/
class InternalTaskEvoluation extends LogManager
{
	private static $_dpl = "*DEBUG* ";

	private $task = null;
	private $number = null;

	public $data_new_format = null;
	public $data_old_format = null;
	public $finish = false;
	private function number() { return $this->number++; }

	private $_GROUP_SECTION_ATTR = array();
	
	function __construct($task, $number=0, $debug=false)
	{
		$this->task = $task;
		$this->number = $number;
		$itb = "\t";
		echo PHP_EOL." -> scenario [ ".__CLASS__."] ";
		// self::_log($this->task, $this->number(), "START: scenario [ ".__CLASS__."] ");

		/*Проверка требований для входа в сценарий.
			Проверить и оповестить есть ли ошибкки => завершение полностью.
						оповестить если нет продолжить сценарий
			Что проверить:
				1.	Ученик может учится только в одной группе по премету. 
				2.
		*/
		$this->finish = $this->checkBeforeConditions();
		if($this->finish) {
			// Запустить основное тело сценария.
			$this->finish = $this->run();
		}

		if($debug){
			echo "\t\t\t>>> Do you want see dump_result(data)? Press 'yes / y / yy / yyy / n' : ";
			$in = trim(fgets(STDIN));
			if($in === "y") $this->runLog($itb, 1);
			else if($in === "yy") $this->runLog($itb, 2);
			else if($in === "yyy") $this->runLog($itb, 3);
			else if($in === "yes") $this->runLog($itb, 0);
		}
		// self::_log($this->task, $this->number(), "FINISH: scenario [ ".__CLASS__."] ");
		echo PHP_EOL."    <- scenario [ ".__CLASS__." ] ".PHP_EOL;
	}
	private function _convertFormat($data_old_format)
	{
		return array(
					"learners" => array(
						"66" => array(
							"learner_obj" => array("id" => "6", "idlearnerdo" => "63", "l_level_id" => null, "_issync" => "0", "removed" => "0", "state" => "0"),
							"groups" => array(
								"33" => array(
									"group_obj" => array(
										"id" => "1", "idsubgroupdo" => "52", "absolutecomplexity" => "8", "idschooldo" => "5", "_issync" => "0", "idmaterialdo" => "3", 
										"idteacherdo" => null, "level" => "1", "idsubjectdo" => "8", "evolution_access" => "1"
										),
									"sections" => array(
										"19" => array(
											"id" => "116", "idsectiondo" => "19", "numbersectiondo" => "1", "st_subgroup_id" => "1", 
											"idsubjectdo" => "8", "levelstart" => "0", "levelcount" => "0", "date" => "2014-02-17" 
											),
										"20" => array(
											"id" => "117", "idsectiondo" => "19", "numbersectiondo" => "1", "st_subgroup_id" => "1", 
											"idsubjectdo" => "8", "levelstart" => "0", "levelcount" => "0", "date" => "2014-02-17" 
											),
									),
									"MUSTMOVED" => array(
										"must_be_removed" => array(),
										"removed" => array()
									),
									"MUSTBE" => array(
										"RESULT" => array(
											"QUESTION_HAVE_LEARNER" => array( "34", "37", "41", "43", "47", "51", "53", "56", "58" ),
											"QUESTION_FOR_MOVE_TO_LEARNER_QMeta" => array(),
											"QUESTION_HISTORY_QMeta" => array(),
											"NEED_MOVE_FROM_HISTORY" => array(),
											"NEED_MOVE_FROM_GROUP" => array(),
											"MOVE_FROM_GROUP" => array("success" => array(), "fail" => array() ),
											"MOVE_FROM_HISTORY" => array("123r" => 123, "success" => array(), "fail" => array() ),
										),
									),
									"MUSTBEMOVEDEXTERNE" => array()
								),
								"34" => array(
									"group_obj" => array(
										"id" => "1", "idsubgroupdo" => "2145", "absolutecomplexity" => "8", "idschooldo" => "5", "_issync" => "0", "idmaterialdo" => "3", 
										"idteacherdo" => null, "level" => "1", "idsubjectdo" => "8", "evolution_access" => "1"
										),
									"sections" => array(
										"19" => array(
											"id" => "116", "idsectiondo" => "19", "numbersectiondo" => "1", "st_subgroup_id" => "1", 
											"idsubjectdo" => "8", "levelstart" => "0", "levelcount" => "0", "date" => "2014-02-17" 
											),
									),
									"MUSTMOVED" => array(
										"must_be_removed" => array(),
										"removed" => array()
									),
									"MUSTBE" => array(
										"RESULT" => array(
											"QUESTION_HAVE_LEARNER" => array( "47", "51", "53", "56", "58" ),
											"QUESTION_FOR_MOVE_TO_LEARNER_QMeta" => array(),
											"QUESTION_HISTORY_QMeta" => array(),
											"NEED_MOVE_FROM_HISTORY" => array(),
											"NEED_MOVE_FROM_GROUP" => array(),
											"MOVE_FROM_GROUP" => array("success" => array(), "fail" => array() ),
											"MOVE_FROM_HISTORY" => array("123r" => 123, "success" => array(), "fail" => array() ),
										),
									),
									"MUSTBEMOVEDEXTERNE" => array()
								),
							),
						),
					),
			);
	}
	private function checkBeforeConditions()
	{
		$errors = array();
		$command = new CDbCommand(Yii::app()->dbY);
		$command->select("y_sp_lg.splg_learner_id, y_sp_lg.splg_subgroup_id, y_sg.idsubgroupdo, y_sg.idsubjectdo, COUNT(y_sg.idsubjectdo) AS clg")
            ->join("y_subgroup y_sg", "y_sp_lg.splg_subgroup_id = y_sg.id")
            ->from("y_splearnergroup y_sp_lg")
            ->group("y_sg.idsubjectdo, y_sp_lg.splg_learner_id")
            ->having("clg > 1");
		$r = $command->queryAll(true);
		if(is_array($r) && count($r))
		{
			$errors[] = "Number of groups in one subject, the student is more than 1 ";
		}
		if(is_array($errors) && count($errors))
		{
			echo PHP_EOL."\t\t*************************************************************************";
			echo PHP_EOL."\t\t*   FATAL ERRORS: \t\t\t\t\t\t\t*";
			foreach ($errors as $error) {
				echo PHP_EOL."\t\t*\t".$error."\t*";
			}
			echo PHP_EOL."\t\t*************************************************************************".PHP_EOL;
			return false;
		}
		else return true;
	}

	public function run()
	{
		for ($i=0; $i < 9999999; $i++) { 
			$y = $i*2;
		}
		$data=array();
		// 4.1 УБРАТЬ У КАЖДОГО УЧЕНИКА ВОПРОСЫ НЕ ИЗ ЕГО ГРУПП ['MUSTBEMOVED']
			$this->data_old_format['MUSTBEMOVED'] = $this->_removeUnnecessary();
	
		// // 4.2 ПОЛУЧИТЬ ТО ЧТО ДОЛЖНО БЫТЬ У КАЖДОГО УЧЕНИКА ГРУППЫ ПО ЕГО ГРУППАМ ['MUSTBE']
			$this->data_old_format['MUSTBE'] = $this->_provideTheNecessary();

		// // 4.3 ПЕРЕНЕСТИ КАЖДОМУ УЧЕНИКУ ВОПРОСЫ ЭКСТЕРНОМ ПО ЕГО ГРУППАМ ['MUSTBEMOVEDEXTERNE']
			$this->data_old_format['MUSTBEMOVEDEXTERNE'] = array();
			$this->data_old_format['MUSTBEMOVEDEXTERNE'] = $this->_provideTheNecessaryExterne();

			$this->data_new_format = $this->_convertFormat($this->data_old_format);
		return array('task'=>$this->task, 'success'=>true, 'number'=>$this->number, 'errors'=>array(), 'data'=>$this->data_new_format);
	}

	private function _removeUnnecessary()
	{
		$data_MUSTBEMOVED['learners'] = array();
		$criteria = new CDbCriteria;
			$criteria->with = array('ySplearnergroups', 'ySplearnergroups.splgSubgroup', );
			$criteria->condition = 't.removed IS FALSE AND 
			ySplearnergroups.evolution_access = TRUE AND 
			ySplearnergroups.deleted = FALSE AND
			splgSubgroup.evolution_access = TRUE';
		$learners = YLearner::model()->findAll($criteria);

		foreach ($learners as $key => $LEARNER) {
			$data_MUSTBEMOVED['learners'][$LEARNER->id] = array();
			$data_MUSTBEMOVED['learners'][$LEARNER->id]['learner_attr'] = $LEARNER->attributes;
			$data_MUSTBEMOVED['learners'][$LEARNER->id]['groups'] = array();
			$data_MUSTBEMOVED['learners'][$LEARNER->id]['must_be_removed'] = array();
			$data_MUSTBEMOVED['learners'][$LEARNER->id]['removed'] = array();

			// Все группы в которых учится ученик
			$LEARNER_GROUP_ID = array();
			foreach ($LEARNER->ySplearnergroups as $key => $splearnergroups) {
				$LEARNER_GROUP_ID[] = $splearnergroups->splg_subgroup_id;
			}
			$data_MUSTBEMOVED['learners'][$LEARNER->id]['groups'] = $LEARNER_GROUP_ID;

			// Все вопросы не из обучаемых(сейчас) групп
			$QUESTIONS_HAVE_LEARNER_later = array();
			$criteria = new CDbCriteria;
				$criteria->condition = 'splq_learner_id = :GROUP_LEARNER_CUR_ID';
				$criteria->params = array(':GROUP_LEARNER_CUR_ID'=>$LEARNER->id,);
				$criteria->addNotInCondition('splq_subgroup_id', $LEARNER_GROUP_ID);						
			$QUESTIONS_HAVE_LEARNER_later = YSPLearnerquestion::model()->findAll($criteria);
			foreach ($QUESTIONS_HAVE_LEARNER_later as $key => $QUESTION_HAVE_LEARNER_later) {
				$data_MUSTBEMOVED['learners'][$LEARNER->id]['must_be_removed'][] = $QUESTION_HAVE_LEARNER_later->id;
			}
			// Удаление вопросов ['must_be_removed']
			QuestionManager::moveLearnerQuestionsToHistory($data_MUSTBEMOVED['learners'][$LEARNER->id]['must_be_removed']);
		}
		return $data_MUSTBEMOVED;
	}
	private function _provideTheNecessary()
	{
		$data_MUSTBE['groups'] = array();
		$criteria = new CDbCriteria;
			$criteria->with = array('ySplearnergroups', /*'ySplearnergroups.splgLearner', */);
			$criteria->condition = 't.evolution_access IS TRUE AND ySplearnergroups.deleted = FALSE';
		$evolution_subgroups = YSubgroup::model()->findAll($criteria);

		foreach ($evolution_subgroups as $key => $GROUP_CUR)
		{
			$Y_GROUP_OBJ = $GROUP_CUR;
			$Y_GROUP_ID = $Y_GROUP_OBJ->id;

			$data_MUSTBE['groups'][$Y_GROUP_ID] = array();
			$data_MUSTBE['groups'][$Y_GROUP_ID]['obj_attr'] = $Y_GROUP_OBJ->attributes;

			$data_MUSTBE['groups'][$Y_GROUP_ID]['sections_attr'] = array();
				// SELECT st.* FROM sectiontiming st WHERE st.idsubgroup = GROUP_CUR_ID AND st.date <= CURRENT_DATE() ORDER BY st.levelstart ASC;
				$criteria = new CDbCriteria;
					$criteria->condition = 'st_subgroup_id = :idsg AND date <= CURRENT_DATE()';
					$criteria->params = array(':idsg'=>$Y_GROUP_ID);		
					$criteria->order = 'levelstart ASC';
				$GROUP_SECTIONS = YSectiontiming::model()->findAll($criteria);
				$GROUP_CUR_ACCESS_SECTIONS = array();
				if(is_array($GROUP_SECTIONS) && count($GROUP_SECTIONS))
				{
					foreach ($GROUP_SECTIONS as $key => $GROUP_SECTION) {
						$GROUP_CUR_ACCESS_SECTIONS[] = $GROUP_SECTION->idsectiondo;
						$data_MUSTBE['groups'][$Y_GROUP_ID]['sections_attr'][$GROUP_SECTION->idsectiondo] = $GROUP_SECTION->attributes;
					}
				}

				// Все возможные секции каждой группы
					$criteria = new CDbCriteria;
						$criteria->condition = 'st_subgroup_id = :idsg';
						$criteria->params = array(':idsg'=>$Y_GROUP_ID);		
						$criteria->order = 'levelstart ASC';
					$GROUP_SECTIONS = YSectiontiming::model()->findAll($criteria);
					if(is_array($GROUP_SECTIONS) && count($GROUP_SECTIONS))
					{
						if(!isset($this->_GROUP_SECTION_ATTR[$Y_GROUP_ID])) $this->_GROUP_SECTION_ATTR[$Y_GROUP_ID] = array();
						else
							throw new Exception("Dublicate group ID on: Get sections of group", 1);

						foreach ($GROUP_SECTIONS as $key => $GROUP_SECTION) {
							$this->_GROUP_SECTION_ATTR[$Y_GROUP_ID][$GROUP_SECTION->idsectiondo] = $GROUP_SECTION->attributes;
						}
					}

			$data_MUSTBE['groups'][$Y_GROUP_ID]['sections'] = $GROUP_CUR_ACCESS_SECTIONS;
							
			$data_MUSTBE['groups'][$Y_GROUP_ID]['qm_all'] = array();
				// qm - has Group (3):
				$criteria = new CDbCriteria;
					$criteria->condition = 'qfg_subgroup_id = :GROUP_CUR_ID';
					$criteria->params = array(':GROUP_CUR_ID'=>$Y_GROUP_ID);
				$ab = YQuestionforgroup::model()->findAll($criteria);
					foreach ($ab as $key => $questionforgroup) { 
						$data_MUSTBE['groups'][$Y_GROUP_ID]['qm_all'][] = $questionforgroup->qfg_questionmeta_id;
					}
			$data_MUSTBE['groups'][$Y_GROUP_ID]['qm_avalsect'] = array();
				// qm - has Group (3,arr_avalSect):
				$criteria = new CDbCriteria;
					$criteria->condition = 'qfg_subgroup_id = :GROUP_CUR_ID';
					$criteria->params = array(':GROUP_CUR_ID'=>$Y_GROUP_ID);
					$criteria->addInCondition('idsectiondo', $GROUP_CUR_ACCESS_SECTIONS);
				$ab = YQuestionforgroup::model()->findAll($criteria);
					foreach ($ab as $key => $questionforgroup) { 
						$data_MUSTBE['groups'][$Y_GROUP_ID]['qm_avalsect'][] = $questionforgroup->qfg_questionmeta_id;
					}

			$data_MUSTBE['groups'][$Y_GROUP_ID]['learners'] = array();
				if(is_array($Y_GROUP_OBJ['ySplearnergroups']) && count($Y_GROUP_OBJ['ySplearnergroups']))
				{
					foreach ($Y_GROUP_OBJ['ySplearnergroups'] as $key => $spLearnerGroup)
					{
						// throw new Exception("Error Processing Request", 1);
						$Y_LEARNER_ID = $spLearnerGroup->splg_learner_id;
						// $data_MUSTBE['groups'][$Y_GROUP_ID]['learners'] = array();
						$data_MUSTBE['groups'][$Y_GROUP_ID]['learners'][$Y_LEARNER_ID]['RESULT_GROUP_ID'] = $Y_GROUP_ID;
						$data_MUSTBE['groups'][$Y_GROUP_ID]['learners'][$Y_LEARNER_ID]['RESULT_GROUP_OBJ'] = $Y_GROUP_OBJ;
						$data_MUSTBE['groups'][$Y_GROUP_ID]['learners'][$Y_LEARNER_ID]['RESULT'] = 
							$this->fillQuestionLearner($Y_LEARNER_ID, $Y_GROUP_ID, $Y_GROUP_OBJ, $GROUP_CUR_ACCESS_SECTIONS);
					}
				}
		}
		return $data_MUSTBE;
	}
	private function _provideTheNecessaryExterne()
	{
		$data_MUSTBEMOVEDEXTERNE['learners'] = array();
		$criteria = new CDbCriteria;
			$criteria->with = array('ySectionlearnercompleteds', 'ySectionlearnercompleteds.slcSubgroup' );
			$criteria->condition = 't.removed IS FALSE AND 
			ySectionlearnercompleteds.id IS NOT NULL AND 
			slcSubgroup.evolution_access = TRUE';
		$learners = YLearner::model()->findAll($criteria);
		// print_r($learners);
		// exit();
		foreach ($learners as $Ylearner)
		{
			$Y_LEARNER_ID = $Ylearner->id;
			$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID]=array();
			// Разбить весь экстерн по группам/предметам.
			$EXTERNE_ALL_LEARNER_by_subgroup_subject = array();
			$EXTERNE_LEARNER_subgroup_subject = array();
			foreach ($Ylearner->ySectionlearnercompleteds as $YSectionlearnercompleted) {
				$EXTERNE_ALL_LEARNER_by_subgroup_subject[$YSectionlearnercompleted->slc_subgroup_id][] = $YSectionlearnercompleted->idsectiondo;
				if(!in_array($YSectionlearnercompleted->slc_subgroup_id, $EXTERNE_LEARNER_subgroup_subject)) {
					$EXTERNE_LEARNER_subgroup_subject[] = $YSectionlearnercompleted->slc_subgroup_id;
					$EXTERNE_LEARNER_subgroup_subject_OBJ[$YSectionlearnercompleted->slc_subgroup_id] = $YSectionlearnercompleted->slcSubgroup;
				}
				$YSectionlearnercompleted->questionsmoved = true;
			}

			// var_dump($EXTERNE_LEARNER_subgroup_subject);
			// Перенести вопросы 
			foreach ($EXTERNE_LEARNER_subgroup_subject as $EXTERNE_SUBGROUP_ID) {
				$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['learner_attr'] = $Ylearner->attributes;
				// Экстерн дял каждого ученика по его группе
				$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['EXTERNE_SECTIONS'] = $EXTERNE_ALL_LEARNER_by_subgroup_subject[$EXTERNE_SUBGROUP_ID];
				// $EXTERNE_SECTIONS_by_one_subgroup_subject = $EXTERNE_ALL_LEARNER_by_subgroup_subject[$EXTERNE_SUBGROUP_ID];
				$Y_GROUP_OBJ = $EXTERNE_LEARNER_subgroup_subject_OBJ[$EXTERNE_SUBGROUP_ID];
				$GROUP_CUR_ACCESS_SECTIONS = $EXTERNE_ALL_LEARNER_by_subgroup_subject[$EXTERNE_SUBGROUP_ID];
				$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['EXTERNE_RESULT_GROUP_ID'] = $EXTERNE_SUBGROUP_ID;
				$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['EXTERNE_RESULT_GROUP_OBJ'] = $Y_GROUP_OBJ;

				// $data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['EXTERNE_RESULT_PARAM'] = array(
				// 	'Y_LEARNER_ID' => $Y_LEARNER_ID,
				// 	'EXTERNE_SUBGROUP_ID' => $EXTERNE_SUBGROUP_ID,
				// 	'Y_GROUP_OBJ' => $Y_GROUP_OBJ,
				// 	'GROUP_CUR_ACCESS_SECTIONS' => $GROUP_CUR_ACCESS_SECTIONS
				// 	);
				$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['EXTERNE_RESULT'] = null;
				$data_MUSTBEMOVEDEXTERNE['learners'][$Y_LEARNER_ID][$EXTERNE_SUBGROUP_ID]['EXTERNE_RESULT'] = 
					$this->fillQuestionLearner($Y_LEARNER_ID, $EXTERNE_SUBGROUP_ID, $Y_GROUP_OBJ, $GROUP_CUR_ACCESS_SECTIONS);
			}
		}
			// Перебор по каждой ВАЛИДНОЙ группе ученика.
			/*
			 * ВАЖНО!!!!! 
			 * Ученик может быть только в одной группе по одному и томуже предмету
			 */
		return $data_MUSTBEMOVEDEXTERNE;
	}



	/**
	 * Fill question learner from group question by sections
	 * @access private
         * @param int idquestiontext
         * 
	 */
	private function fillQuestionLearner($Y_LEARNER_ID, $Y_GROUP_ID, $Y_GROUP_OBJ, $GROUP_CUR_ACCESS_SECTIONS )
	{
		// echo "[ ".$Y_LEARNER_ID. " " . $Y_GROUP_ID. " <" . (( count($GROUP_CUR_ACCESS_SECTIONS) ) ? implode(":", $GROUP_CUR_ACCESS_SECTIONS) : "null") . "> ]";

		$data_fillQuestionLearner = array();
		
		// Вопросы ученика
			$QUESTION_HAVE_LEARNER = array();
				$criteria = new CDbCriteria;
					$criteria->condition = 'splq_learner_id = :GROUP_LEARNER_CUR_ID AND splq_subgroup_id = :GROUP_CUR_ID';
					$criteria->params = array(':GROUP_LEARNER_CUR_ID'=>$Y_LEARNER_ID, ':GROUP_CUR_ID'=>$Y_GROUP_ID);
				$ab = YSPLearnerquestion::model()->findAll($criteria);
				foreach ($ab as $key => $questionlearner) {
					$QUESTION_HAVE_LEARNER[] = $questionlearner->splq_questionmeta_id;
				}
			$data_fillQuestionLearner['QUESTION_HAVE_LEARNER'] = $QUESTION_HAVE_LEARNER;

		// Вопросы группы исключая вопросы ученика, что у него уже есть
			$QUESTION_FOR_MOVE_TO_LEARNER_QMeta = array();
				$criteria = new CDbCriteria;
					$criteria->condition = 'qfg_subgroup_id = :GROUP_CUR_ID';
					$criteria->params = array(':GROUP_CUR_ID'=>$Y_GROUP_ID);
					$criteria->addNotInCondition('qfg_questionmeta_id', $QUESTION_HAVE_LEARNER);
					$criteria->addInCondition('idsectiondo', $GROUP_CUR_ACCESS_SECTIONS);
				$ab = YQuestionforgroup::model()->findAll($criteria);
				foreach ($ab as $key => $questionforgroup) {
					$QUESTION_FOR_MOVE_TO_LEARNER_QMeta[] = $questionforgroup->qfg_questionmeta_id;
				}
			$data_fillQuestionLearner['QUESTION_FOR_MOVE_TO_LEARNER_QMeta'] = $QUESTION_FOR_MOVE_TO_LEARNER_QMeta;

		// # Весь спивок вопросов из HISTORY ученика GROUP_LEARNER_CUR_ID по предмету QUESTION_FOR_MOVE_TO_LEARNER['idsubjectdo']
			$QUESTION_HISTORY_QMeta = array();
				//         SELECT slqh.*
				//           FROM spisoklearnerquestionhistory slqh
				//           WHERE slqh.idlearner = GROUP_LEARNER_CUR_ID AND slqh.idsubjectdo = QUESTION_FOR_MOVE_TO_LEARNER['idsubjectdo'];
				$criteria = new CDbCriteria;
					$criteria->condition = 'splqh_learner_id = :GROUP_LEARNER_CUR_ID AND idsubjectdo = :GROUP_CUR_SUBJECT_DO';
					$criteria->params = array(':GROUP_LEARNER_CUR_ID'=>$Y_LEARNER_ID, ':GROUP_CUR_SUBJECT_DO'=>$Y_GROUP_OBJ->idsubjectdo);
					$criteria->addInCondition('splqh_questionmeta_id', $QUESTION_FOR_MOVE_TO_LEARNER_QMeta);
				$abc = YSPLearnerquestionhistory::model()->findAll($criteria);
					foreach ($abc as $key => $questionhistory) {
						$QUESTION_HISTORY_QMeta[] = $questionhistory->splqh_questionmeta_id;
					}
			$data_fillQuestionLearner['QUESTION_HISTORY_QMeta'] = $QUESTION_HISTORY_QMeta;

		// # Сравнить множество вопросов для ученика GROUP_LEARNER_CUR_ID с HISTORY
			// то что нужно переносить через HISTORY
				$historyExistQuestionMeta = array_intersect($QUESTION_FOR_MOVE_TO_LEARNER_QMeta, $QUESTION_HISTORY_QMeta);
				$data_fillQuestionLearner['NEED_MOVE_FROM_HISTORY'] = $historyExistQuestionMeta;
					foreach ($abc as $key => $questionhistory) {
						if(array_search($questionhistory->splqh_questionmeta_id, $historyExistQuestionMeta) !== FALSE) {
							$QUESTION_HISTORY_IDKey_QM[$questionhistory->splqh_questionmeta_id] = $questionhistory;
						}
					}
			// то что нужно переносить напрямую
				$NewQuestionMeta = array_diff($QUESTION_FOR_MOVE_TO_LEARNER_QMeta, $historyExistQuestionMeta);
				$data_fillQuestionLearner['NEED_MOVE_FROM_GROUP'] = $NewQuestionMeta;
							
			// # Записи самих вопросов, что должны и будут перенесены ученику
				$criteria = new CDbCriteria;
					$criteria->with = array('yQuestionforgroups', );
					$criteria->condition = 'yQuestionforgroups.qfg_subgroup_id = :sg';
					$criteria->params = array(':sg'=>$Y_GROUP_ID);
					$criteria->addInCondition('qfg_questionmeta_id', $QUESTION_FOR_MOVE_TO_LEARNER_QMeta);
				$questionsGroup = YQuestionmeta::model()->findAll($criteria);
					foreach ($questionsGroup as $key => $qm) { $questionMetaIdKeys[$qm->id] = $qm; }


				$success = array(); $fail=array();
					foreach ($NewQuestionMeta as $key => $QuestMetaID) {
						$r = $this->saveInQuestionLearner(
							$Y_GROUP_OBJ->id, 
							$Y_LEARNER_ID, 
							$questionMetaIdKeys[$QuestMetaID], 
							null 
						);
						if ($r) $success[] = $QuestMetaID;
						else $fail[] = $QuestMetaID;
					}
					$data_fillQuestionLearner['MOVE_FROM_GROUP']['success'] = $success;
					$data_fillQuestionLearner['MOVE_FROM_GROUP']['fail'] = $fail;
				
				$success = array(); $fail=array();
					foreach ($historyExistQuestionMeta as $key => $QuestMetaID) {
						$r = $this->saveInQuestionLearner(
							$Y_GROUP_OBJ->id, 
							$Y_LEARNER_ID, 
							$questionMetaIdKeys[$QuestMetaID], 
							$QUESTION_HISTORY_IDKey_QM[$QuestMetaID]
						);
						$questHistory = $QUESTION_HISTORY_IDKey_QM[$QuestMetaID];
						if ($r && $questHistory->delete()) $success[] = $QuestMetaID;
						else $fail[] = $QuestMetaID;
					}
					$data_fillQuestionLearner['MOVE_FROM_HISTORY']['123r'] = 123;
					$data_fillQuestionLearner['MOVE_FROM_HISTORY']['success'] = $success;
					$data_fillQuestionLearner['MOVE_FROM_HISTORY']['fail'] = $fail;

				// // LOG SAVE
				// 	$LEARNER = $data_fillQuestionLearner;
				// 	self::_log(
				// 		$this->task,
				// 		$this->number(), 
				// 		"GROUP: ".$Y_GROUP_ID.
				// 			" LEARNER: " . $Y_LEARNER_ID.
				// 			" MOVED: " . (count($LEARNER['MOVE_FROM_GROUP']['success'])+count($LEARNER['MOVE_FROM_HISTORY']['success'])) . " questions",
				// 		null, true, $data_fillQuestionLearner
				// 	);
		return $data_fillQuestionLearner;
	}

	/**
	 * Save question for learner storage. Checking SPLearnerQuestionHistory storage.
	 * @access private
         * @param int Y_GR_ID
         * @param int Y_LEARNER_ID
         * @param int questionmeta
         * @param int SPLearnerQuestionHistory
         * @param int avalSections
         * 
	 */
	private function saveInQuestionLearner($Y_GR_ID, $Y_LEARNER_ID, $questionmeta=null, $SPLearnerQuestionHistory=null)
	{
		// INSERT (mark, countrightanswer, countallanswer из spisoklearnerquestionhistory, relativecomplexity и absolutecomplexity наследуем из questionmeta
		// INSERT (countrightanswer = 0, countallanswer = 0, relativecomplexity и absolutecomplexity наследуем из questionmeta

		if(!$questionmeta || !$this->_GROUP_SECTION_ATTR) throw new Exception("variable questionmeta is null", 1);
		$mark = null;
		if($SPLearnerQuestionHistory!=null)
		{
			$mark = $SPLearnerQuestionHistory->mark;
			$countrightanswer = $SPLearnerQuestionHistory->countrightanswer;
			$countallanswer = $SPLearnerQuestionHistory->countallanswer;
		}
		else
		{
			$countrightanswer = 0;
			$countallanswer = 0;
		}
		$relativecomplexity = $questionmeta->relativecomplexity;
		$absolutecomplexity = $questionmeta->absolutecomplexity;
		
		$model = new YSPLearnerquestion;
		// questionmeta
		$model->dateblock = $questionmeta->dateblock;
		$model->splq_questionmeta_id = $questionmeta->id;
		// questionforgroup
		$model->idownerdo = $questionmeta->yQuestionforgroups[0]->idownerdo;
		$model->idmaterialdo = $questionmeta->yQuestionforgroups[0]->idmaterialdo;
		$model->idsectiondo = $questionmeta->yQuestionforgroups[0]->idsectiondo;
		$model->idparagraphdo = $questionmeta->yQuestionforgroups[0]->idparagraphdo;
		$model->idquestiondo = $questionmeta->yQuestionforgroups[0]->idquestiondo;
		$model->idsubjectdo = $questionmeta->yQuestionforgroups[0]->idsubjectdo;
		$model->isborrowed = $questionmeta->yQuestionforgroups[0]->isborrowed;
		
		$model->splq_learner_id = $Y_LEARNER_ID;
		$model->splq_subgroup_id = $Y_GR_ID;
		// echo PHP_EOL."Press Enter".PHP_EOL;
		// print_r($avalSections[$model->idsectiondo]['levelstart']);
		// print PHP_EOL;
		// $a = fgets(STDIN);
		// var_dump($Y_GR_ID);
		// var_dump($model->idsectiondo);
		// var_dump($this->_GROUP_SECTION_ATTR);
		$model->level = $this->_GROUP_SECTION_ATTR[$Y_GR_ID][$model->idsectiondo]['levelstart'];
		
		if($mark) 
			$model->mark = $mark;
		$model->countrightanswer = $countrightanswer;
		$model->countallanswer = $countallanswer;
		$model->relativecomplexity = $relativecomplexity;
		$model->absolutecomplexity = $absolutecomplexity;

		// array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, idlearner, idquestionmeta, isborrowed, level', 'required'),

		// print_r($model);
		// echo PHP_EOL."Press Enter".PHP_EOL;
		// $a = fgets(STDIN);
		$rez = $model->save(true);
		if(!$rez) {
			print_r($model->getErrors());
			// exit();
		}
		return $rez;
	}



	public function runLog($itb="", $index=0)
	{
		$itab = $itb;
		$itab_l0 = PHP_EOL.$itab; 
		$itab_l1 = $itab_l0."\t"; 
		$itab_l2 = $itab_l1."\t";
		$itab_l3 = $itab_l2."\t";
		$itab_l4 = $itab_l3."\t";
		$itab_l5 = $itab_l4."\t";

		# "4.1 |MUSTBEMOVED|"
		if($index==1 || $index===0) {
			echo PHP_EOL.$itab_l0."4.1 |MUSTBEMOVED|"; // ['learner_attr'] ['groups'] ['must_be_removed'] ['must_be_removed_obj'] ['removed']
			foreach ($this->data_old_format['MUSTBEMOVED']['learners'] as $Y_LEARNER_ID => $Y_LEARNER)
			{
				echo $itab_l1."===============================================================================================";
				echo $itab_l1."|>>>>>> LEARNER: ".$Y_LEARNER_ID;

				echo "\tattributes : ";
					$itab = $itab_l2;
					echo "\t(idY, idlearnerdo, l_level_id, _issync, removed, state)\t";
					echo $itab."\t\t\t\t".implode(",\t", $Y_LEARNER['learner_attr'] );
				echo $itab_l2."=======================================================================================";
				if(count($Y_LEARNER['removed'])) {
					echo PHP_EOL.$itab_l2."groups ";
						$itab = $itab_l3;
							echo "\t\tcount=".count($Y_LEARNER['groups'])." : ";
							echo "\t".implode(', ', $Y_LEARNER['groups'] );
							
					echo PHP_EOL.$itab_l2."must_removed ";
						$itab = $itab_l2;
							echo "\tcount=".count($Y_LEARNER['must_be_removed'])." : ";
							echo "\t".implode(', ', $Y_LEARNER['must_be_removed'] );
							echo $itab."(splq->id)";

					echo PHP_EOL.$itab_l2."removed : ";
						$itab = $itab_l2;
							echo "\tcount=".count($Y_LEARNER['removed'])." : ";
							echo $itab."\t".implode(', ', $Y_LEARNER['removed'] );					
				}
				else {
					$bor_ = $itab_l2."* ";
					echo PHP_EOL.$bor_."**********************************************************";
						echo $bor_."NOTHING MOVE TO HISTORY";
					echo $bor_."**********************************************************";
				}

			}
		}
		# "4.2 |MUSTBE|"
		if($index==2 || $index===0) {
			echo PHP_EOL.$itab_l0."4.2 |MUSTBE|"; // ['']
			foreach ($this->data_old_format['MUSTBE']['groups'] as $Y_GROUP_ID => $Y_GROUP)
			{
				echo $itab_l1."===============================================================================================";
				echo $itab_l1."|>>>>>> GROUP ID: ".$Y_GROUP_ID;
					$tmp = $Y_GROUP['obj_attr'];
				echo "\tattributes : ";
					$itab = $itab_l5;
					echo "\t idY \t\t\t: ". $tmp['id'];
					echo "\t idsubgroupdo \t\t: ". $tmp['idsubgroupdo'];
					echo $itab."\t idschooldo \t\t: ". $tmp['idschooldo'];
					echo "\t idmaterialdo \t\t: ". $tmp['idmaterialdo'];
					echo $itab."\t idteacherdo \t\t: ". $tmp['idteacherdo'];
					echo "\t level \t\t\t: ". $tmp['level'];
					echo $itab."\t idsubjectdo \t\t: ". $tmp['idsubjectdo'];
					echo "\t evolution_access \t: ". $tmp['evolution_access'];
					// echo $itab_l4."\t absolutecomplexity \t: ". $tmp['absolutecomplexity'];
					// echo "\t _issync \t\t: ". $tmp['_issync'];
				echo $itab_l2."=======================================================================================";
				echo $itab_l2."sections : ";
					$itab = $itab_l2;
						if(is_array($Y_GROUP['sections_attr']))
						{
							echo "\tcount=".count($Y_GROUP['sections_attr']). " : ";
							// echo "\t".implode(", ", $Y_GROUP['sections_attr']);
							foreach ($Y_GROUP['sections_attr'] as $GROUP_SECTION_idsectiondo => $GROUP_SECTION) {
								echo $GROUP_SECTION['id']."/".$GROUP_SECTION['idsectiondo']." ";
							}
						}

						$itab = $itab."\t\t";
					echo PHP_EOL.$itab."qm - has Group (".$Y_GROUP_ID."):\t\t\t count=".count($Y_GROUP['qm_all'])."\t"; 
					echo "{".implode(", ", $Y_GROUP['qm_all'])."}";
					
					echo $itab."qm - has Group (".$Y_GROUP_ID.",arr_avalSect):\t count=".count($Y_GROUP['qm_avalsect'])."\t"; 
					echo "{".implode(", ", $Y_GROUP['qm_avalsect'])."}";

				echo $itab_l2."learners : ";
					$itab = $itab_l2;
					if(is_array($Y_GROUP['learners']))
					{
						echo "\tcount(GROUP_CUR[ splearnergroups ]): ".count($Y_GROUP['learners']);
						foreach ($Y_GROUP['learners'] as $Y_LEARNER_ID => $YlearnerComplex)
						{
							$LEARNER = $YlearnerComplex['RESULT'];
							$Y_GROUP_ID = $YlearnerComplex['RESULT_GROUP_ID'];
							$Y_GROUP_OBJ = $YlearnerComplex['RESULT_GROUP_OBJ'];

							$this->_partLog("\t", $LEARNER, $Y_LEARNER_ID, $Y_GROUP_ID, $Y_GROUP_OBJ);
						}
					}
			}
		}
		# "4.3 |MUSTBEMOVEDEXTERNE|"
		if($index==3 || $index===0) {
			echo PHP_EOL.$itab_l0."4.3 |MUSTBEMOVEDEXTERNE|"; // ['']
			// var_dump( $this->data_old_format['MUSTBEMOVEDEXTERNE'] ); exit();
			foreach ($this->data_old_format['MUSTBEMOVEDEXTERNE']['learners'] as $Y_LEARNER_ID => $SubgroupComplex) {
				foreach ($SubgroupComplex as $Y_SUBGROUP_ID => $YGroupLearnerComplex) {

					// EXTERNE_RESULT_GROUP_ID


					$Ylearner = $YGroupLearnerComplex['learner_attr'];
					$YlearnerEXTERNE = $YGroupLearnerComplex['EXTERNE_SECTIONS'];

					echo $itab_l1."|>>>>>> LEARNER ID Y/DO: ".$Y_LEARNER_ID;
						echo "\tLearner: ".implode(", ",$Ylearner).PHP_EOL;
							echo $itab_l2."ID SUBGROUP_ID: \t  EXTERNE SECT :";
								echo $itab_l2."  ".$Y_SUBGROUP_ID."\t\t\t\t\t".implode(', ', $YlearnerEXTERNE);
							if( isset($YGroupLearnerComplex['EXTERNE_RESULT']) )
							{
								$LEARNER = $YGroupLearnerComplex['EXTERNE_RESULT'];
								$Y_GROUP_ID = $YGroupLearnerComplex['EXTERNE_RESULT_GROUP_ID'];
								$Y_GROUP_OBJ = $YGroupLearnerComplex['EXTERNE_RESULT_GROUP_OBJ'];

								$this->_partLog("\t", $LEARNER, $Y_LEARNER_ID, $Y_GROUP_ID, $Y_GROUP_OBJ);
							}
				}
			}
		}
	}

	private function _partLog($itb, $LEARNER, $Y_LEARNER_ID, $Y_GROUP_ID, $Y_GROUP_OBJ)
	{
		$itab = $itb;
		$itab_l0 = PHP_EOL.$itab; 
		$itab_l1 = $itab_l0."\t"; 
		$itab_l2 = $itab_l1."\t";
		$itab_l3 = $itab_l2."\t";
		$itab_l4 = $itab_l3."\t";
		$itab_l5 = $itab_l4."\t";
		/// Double code
			echo $itab_l3."l: ".$Y_LEARNER_ID;
			$itab = $itab_l3."\t";

			// Вопросы ученика
			// $data['MUSTBE'][$Y_GROUP_ID]['learners'][$Y_LEARNER_ID]['QUESTION_HAVE_LEARNER'] = $QUESTION_HAVE_LEARNER;
			echo $itab."qm - has Learner(".$Y_LEARNER_ID.",".$Y_GROUP_ID."): \t\t count=".count($LEARNER['QUESTION_HAVE_LEARNER'])."\t";
			echo "{".implode(", ", $LEARNER['QUESTION_HAVE_LEARNER'])."}";
			
			// Вопросы группы исключая вопросы ученика, что у него уже есть
			// $data['MUSTBE'][$Y_GROUP_ID]['learners'][$Y_LEARNER_ID]['QUESTION_FOR_MOVE_TO_LEARNER_QMeta'] = $QUESTION_FOR_MOVE_TO_LEARNER_QMeta;
			echo PHP_EOL.$itab."qm - questionGroup without L&Sec(arr,".$Y_GROUP_ID."): count=".count($LEARNER['QUESTION_FOR_MOVE_TO_LEARNER_QMeta'])."\t";
			echo "{".implode(", ", $LEARNER['QUESTION_FOR_MOVE_TO_LEARNER_QMeta'])."}";

			// Весь спивок вопросов из HISTORY ученика GROUP_LEARNER_CUR_ID по предмету QUESTION_FOR_MOVE_TO_LEARNER['idsubjectdo']
			// $data['MUSTBE'][$Y_GROUP_ID]['learners'][$Y_LEARNER_ID]['QUESTION_HISTORY_QMeta'] = $QUESTION_HISTORY_QMeta;
			echo $itab."qm - question in HISTORY(".$Y_LEARNER_ID.",".$Y_GROUP_OBJ->idsubjectdo."): \t count=".count($LEARNER['QUESTION_HISTORY_QMeta'])."\t";
			echo "{".implode(", ", $LEARNER['QUESTION_HISTORY_QMeta'])."}";

			// # Сравнить множество вопросов для ученика GROUP_LEARNER_CUR_ID с HISTORY
			// ....
			// то что нужно переносить через HISTORY
			// 	...
			// то что нужно переносить напрямую
			// 	...
			// Записи самих вопросов, что должны и будут перенесены ученику
			// 	...
			$bor_ = $itab_l4."* ";
			echo PHP_EOL.$bor_."**********************************************************";
				if(count($LEARNER['MOVE_FROM_GROUP']['success']) && 
					count($LEARNER['MOVE_FROM_GROUP']['fail']) && 
					count($LEARNER['MOVE_FROM_HISTORY']['success']) && 
					count($LEARNER['MOVE_FROM_HISTORY']['fail']))
				{
					echo $bor_."save QM from GROUP: ";
					echo "\t\tsuccess = {".implode(", ", $LEARNER['MOVE_FROM_GROUP']['success'])."}";
					echo "\tfail = {".implode(", ", $LEARNER['MOVE_FROM_GROUP']['fail'])."}";

					echo $bor_."save QM from HISTORY: ";
					echo "\tsuccess = {".implode(", ", $LEARNER['MOVE_FROM_HISTORY']['success'])."}";
					echo "\tfail = {".implode(", ", $LEARNER['MOVE_FROM_HISTORY']['fail'])."}";					
				}
				else echo $bor_."NOTHING MOVE";
			echo $bor_."**********************************************************";
		/// End double code
	}

}
?>