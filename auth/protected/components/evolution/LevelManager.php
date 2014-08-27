<?php

/**
 * @access public
 * @author CHERRY
 */
class LevelManager {

	/**
	 * @access public
	 * @param int idlevel
	 * @ParamType idlevel int
	 */
	public function finishLevel($idlevel) {
		throw new Exception("Not yet implemented");
	}

	/**
	 * @access public
	 * @param int idlearner
	 * @param JLLevel level
	 * @ParamType idlearner int
	 * @ParamType level JLLevel
	 */
	public function analyzeQuestionSufficiency($idlearner, $level) {
		throw new Exception("Not yet implemented");
	}

	/**
     * Пересчет множеств ONCE
	 * @access public
	 * @param array massIdSubgroup
	 * @ParamType massIdSubgroup array
	 */
	public static function recalculateAvailableQuestions($massIdSubgroup, $idLearner) {
		// throw new Exception("Not yet implemented");
	  //   $subgroupRatio = Subgroupratio::model()->find( 
	  //   	array(
			// 	'select'=>array('*'),
			// 	'condition'=>'idsubgroup=:idsubgroup',
			// 	'params' => array(':idsubgroup'=>$idSubgroup, ),
			// )
	  //   );

	   //  Learner::model()->updateAll(
	   //  	array(
	   //  		'attributes'=> array('idlearnerdo' => 4),
	   //  		'condition'=>'id=:id',
				// 'params' => array(':id'=>37, ),
	   //  		)
	   //  );
	   //  exit();
	   //  $criteria = new CDBCriteria();
	   //  $criteria->addCondition("");
	   //  Learner::model()->updateAll(
	   //  	array('idlearnerdo' => 4),
	   //  	'id=:id',
	   //  	array(':id'=>37));
	   //  exit();


	    // Yii::app()->dbY->createCommand()
	    // 	->update(
	    // 		'learner', 
	    // 		array('idlearnerdo'=>4,), 
	    // 		'id=:id', 
	    // 		array(':id'=>37)

	    // 		// ->update('learner')
	    // 		// ->set('idlearnerdo')
	    // 	);



		$subgroupRatio = Yii::app()->dbY->createCommand()
                ->select('*')
                ->from('y_subgroupratio')
                ->where(array('in', 'sgr_subgroup_id', $massIdSubgroup))
                ->queryAll();

//	     print_r($subgroupRatio); exit();

	    foreach($subgroupRatio as $oneSubgroupKey => $value)
		{
	    	# Количество вопросов всего доступных этого предмета
            $countEasyMiddleHardQuestions = $value['kpriority'] * 45;
		    # Количество популярных учителя
            $countTP = $countEasyMiddleHardQuestions * $value['popularkteacher'];
		    # Количество непопулярных учителя
            $countTUP = $countEasyMiddleHardQuestions * $value['unpopularkteacher'];
            # Количество одолженых популярных
		    $countBP = $countEasyMiddleHardQuestions * $value['popularkborrowed'];
            # Количество одолженых непопулярных
		    $countBUP = $countEasyMiddleHardQuestions * $value['unpopularkborrowed'];

            # уникальный ключ группы
            $sgr_subgroup_id = $value['sgr_subgroup_id'];

            /**
             *massTPRSSE - massTeacherPopularRevewSectionSectionExtern
             *massTPRSSE = array(
             *      [idSubgroup]=>array(
             *         [isTeacher]=>array(
             *            [isPopular]=>array(
             *               [activeState]=>array(
             *                  [LAG]=>
             *                  [ACTUAL]=>
             *                  [ADVANCE]=>
             *                )
             *              )
             *          )
             *       )
             *);
             */
            $massTPRSSE = array();


			###### LAG (отстающие) ######

				$massTPRSSE[$sgr_subgroup_id][1][1][1]['LAG'] = $countTP * $value['lagkreview'];
				$massTPRSSE[$sgr_subgroup_id][1][1][2]['LAG'] = $countTP * $value['lagkactivestudyduration'];
				
				$massTPRSSE[$sgr_subgroup_id][1][0][1]['LAG'] = $countTUP * $value['lagkreview'];
				$massTPRSSE[$sgr_subgroup_id][1][0][2]['LAG'] = $countTUP * $value['lagkactivestudyduration'];

				$massTPRSSE[$sgr_subgroup_id][0][1][1]['LAG'] = $countBP * $value['lagkreview'];
				$massTPRSSE[$sgr_subgroup_id][0][1][2]['LAG'] = $countBUP * $value['lagkactivestudyduration'];

				$massTPRSSE[$sgr_subgroup_id][0][0][1]['LAG'] = $countTUP * $value['lagkreview'];
				$massTPRSSE[$sgr_subgroup_id][0][0][2]['LAG'] = $countTUP * $value['lagkactivestudyduration'];

			###### ACTUAL (нормальные) ######

				$massTPRSSE[$sgr_subgroup_id][1][1][1]['ACTUAL'] = $countTP * $value['actualkreview'];
				$massTPRSSE[$sgr_subgroup_id][1][1][2]['ACTUAL'] = $countTP * $value['actualkactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][1][1][3]['ACTUAL'] = $countTP * $value['actualkactivesection'];


				$massTPRSSE[$sgr_subgroup_id][1][0][1]['ACTUAL'] = $countTUP * $value['actualkreview'];
				$massTPRSSE[$sgr_subgroup_id][1][0][2]['ACTUAL'] = $countTUP * $value['actualkactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][1][0][3]['ACTUAL'] = $countTUP * $value['actualkactivesection'];


				$massTPRSSE[$sgr_subgroup_id][0][1][1]['ACTUAL'] = $countBP * $value['actualkreview'];
				$massTPRSSE[$sgr_subgroup_id][0][1][2]['ACTUAL'] = $countBP * $value['actualkactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][0][1][3]['ACTUAL'] = $countBP * $value['actualkactivesection'];


				$massTPRSSE[$sgr_subgroup_id][0][0][1]['ACTUAL'] = $countBUP * $value['actualkreview'];
				$massTPRSSE[$sgr_subgroup_id][0][0][2]['ACTUAL'] = $countBUP * $value['actualkactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][0][0][3]['ACTUAL'] = $countBUP * $value['actualkactivesection'];

			###### ADVANCE (экстерн) ######

				$massTPRSSE[$sgr_subgroup_id][1][1][1]['ADVANCE'] = $countTP * $value['advancekreview'];
				$massTPRSSE[$sgr_subgroup_id][1][1][2]['ADVANCE'] = $countTP * $value['advancekactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][1][1][3]['ADVANCE'] = $countTP * $value['advancekactivesection'];
				$massTPRSSE[$sgr_subgroup_id][1][1][4]['ADVANCE'] = $countTP * $value['advancekextern'];


				$massTPRSSE[$sgr_subgroup_id][1][0][1]['ADVANCE'] = $countTUP * $value['advancekreview'];
				$massTPRSSE[$sgr_subgroup_id][1][0][2]['ADVANCE'] = $countTUP * $value['advancekactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][1][0][3]['ADVANCE'] = $countTUP * $value['advancekactivesection'];
				$massTPRSSE[$sgr_subgroup_id][1][0][4]['ADVANCE'] = $countTUP * $value['advancekextern'];


				$massTPRSSE[$sgr_subgroup_id][0][1][1]['ADVANCE'] = $countBP * $value['advancekreview'];
				$massTPRSSE[$sgr_subgroup_id][0][1][2]['ADVANCE'] = $countBP * $value['advancekactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][0][1][3]['ADVANCE'] = $countBP * $value['advancekactivesection'];
				$massTPRSSE[$sgr_subgroup_id][0][1][4]['ADVANCE'] = $countBP * $value['advancekextern'];


				$massTPRSSE[$sgr_subgroup_id][0][0][1]['ADVANCE'] = $countBUP * $value['advancekreview'];
				$massTPRSSE[$sgr_subgroup_id][0][0][2]['ADVANCE'] = $countBUP * $value['advancekactivestudyduration'];
				$massTPRSSE[$sgr_subgroup_id][0][0][3]['ADVANCE'] = $countBUP * $value['advancekactivesection'];
				$massTPRSSE[$sgr_subgroup_id][0][0][4]['ADVANCE'] = $countBUP * $value['advancekextern'];
				
//				 print_r($massTPRSSE);
				
				$minCountLAG = 10; $minCountACTUAL = 10; $minCountADVANCE = 10;

				foreach($massTPRSSE as $sgr_subgroup_id => $oneSubgroup)
				{	
					foreach($oneSubgroup as $flagTeacherOwner => $isTeacherOwner)
					{	
						foreach($isTeacherOwner as $flagPopular => $isPopular)
						{	
							foreach($isPopular as $activeState => $currentActiveState)
							{
								if($currentActiveState['LAG'] < $minCountLAG && $currentActiveState['LAG'] > 0.1)
									$minCountLAG = $currentActiveState['LAG'];
								
								if($currentActiveState['ACTUAL'] < $minCountACTUAL && $currentActiveState['ACTUAL'] > 0.1)
									$minCountACTUAL = $currentActiveState['ACTUAL'];

								if($currentActiveState['ADVANCE'] < $minCountADVANCE && $currentActiveState['ADVANCE'] > 0.1)
									$minCountADVANCE = $currentActiveState['ADVANCE'];
							}
						}
					}
				}

				foreach($massTPRSSE as $sgr_subgroup_id => $oneSubgroup)
				{	
					foreach($oneSubgroup as $flagTeacherOwner => $isTeacherOwner)
					{	
						foreach($isTeacherOwner as $flagPopular => $isPopular)
						{	
							foreach($isPopular as $activeState => $currentActiveState)
							{
								if($currentActiveState['LAG'])
									$massTPRSSE[$sgr_subgroup_id][$flagTeacherOwner][$flagPopular][$activeState]['LAG'] =
                                        round($currentActiveState['LAG'] / $minCountLAG);
								
								if($currentActiveState['ACTUAL'])
									$massTPRSSE[$sgr_subgroup_id][$flagTeacherOwner][$flagPopular][$activeState]['ACTUAL'] =
                                        round($currentActiveState['ACTUAL'] / $minCountACTUAL);

								if($currentActiveState['ADVANCE'])
									$massTPRSSE[$sgr_subgroup_id][$flagTeacherOwner][$flagPopular][$activeState]['ADVANCE'] =
                                        round($currentActiveState['ADVANCE'] / $minCountADVANCE);
							}
						}
					}
				}

				for($relativecomplexity = 1; $relativecomplexity < 4; $relativecomplexity++)
				{
					foreach($massTPRSSE as $sgr_subgroup_id => $oneSubgroup)
					{	
                        foreach($oneSubgroup as $flagTeacherOwner => $isTeacherOwner)
						{	
							foreach($isTeacherOwner as $flagPopular => $isPopular)
							{	
								foreach($isPopular as $activeState => $currentActiveState)
								{
									
									

									$attributesToUpdate = array();
									if(isset($currentActiveState['LAG'])){
										$attributesToUpdate["lagcountavailableall"] = $currentActiveState['LAG'];
									}if(isset($currentActiveState['ACTUAL'])){
										$attributesToUpdate["actualcountavailableall"] = $currentActiveState['ACTUAL'];
									}if(isset($currentActiveState['ADVANCE'])){
										$attributesToUpdate["advancecountavailableall"] = $currentActiveState['ADVANCE'];
									}
									// print_r($attributesToUpdate);

                                    $condition = 'relativecomplexity=:relativecomplexity
                                        AND isteacherownerofset=:isteacherownerofset AND ispopular=:ispopular AND
                                        actualitystate=:actualitystate AND slq_subgroup_id=:slq_subgroup_id';


                                    $params = array(
                                        ':slq_subgroup_id'=>$sgr_subgroup_id,
                                        ':relativecomplexity' => $relativecomplexity,
                                        ':isteacherownerofset' => $flagTeacherOwner,
                                        ':ispopular' => $flagPopular,
                                        ':actualitystate' => $activeState);

                                    if($idLearner != NULL)
                                    {
                                        $condition .= ' AND slq_learner_id=:idLearner';
                                        $params[':idLearner'] = $idLearner;
                                    }


                                    YSetlearnerquestion::model()->updateAll(
                                        $attributesToUpdate,
                                        $condition,
                                        $params
                                    );
                                    // ':idlearner' => $idLearnerY,

                                    // $i++;
                                    // if($i == 4)
                                    // 	exit();




                                    // $value = $currentActiveState['LAG'];
									// Yii::app()->dbY->createCommand (
									// 	"UPDATE setlearnerquestion SET lagcountavalaibleall = $value WHERE relativecomplexity=:relativecomplexity AND isteacherownerofset=:isteacherownerofset AND ispopular=:ispopular AND actualitystate=:actualitystate AND idlearner=:idlearner AND idsubgroup=:idsubgroup")
									// ->bindValues(array(
									// 	':relativecomplexity' => $relativecomplexity, 
									// 	':isteacherownerofset' => $flagTeacherOwner, 
									// 	':ispopular' => $flagPopular, 
									// 	':actualitystate' => $activeState, 
									// 	':idlearner' => $idLearnerY, 
									// 	':idsubgroup'=>$idSubgroup))
									// ->execute();



									// $criteria = new CDBCriteria();
									// $criteria->condition ="relativecomplexity=:relativecomplexity AND isteacherownerofset=:isteacherownerofset AND ispopular=:ispopular AND actualitystate=:actualitystate AND idlearner=:idlearner AND idsubgroup=:idsubgroup";
									// $ctiteria->params = array(
									// 		':relativecomplexity' => $relativecomplexity, 
									// 		':isteacherownerofset' => $flagTeacherOwner, 
									// 		':ispopular' => $flagPopular, 
									// 		':actualitystate' => $activeState, 
									// 		':idlearner' => $idLearnerY, 
									// 		':idsubgroup'=>$idSubgroup);



									// $mass = ;
									// $criteria = new CDBCriteria();
									// $criteria->condition ="idlearnerdo=:idlearnerdo";
									// $ctiteria->params = array(
									// 		':idlearnerdo' => 77);

									// Learner::model()->updateAll(
									// 	array('state'=>1, 'removed'=>1),
									// 	$criteria);
									// exit();

									// if(isset($currentActiveState['LAG'])){
									// 	Yii::app()->dbY->createCommand (
									// 		"UPDATE setlearnerquestion SET lagcountavalaibleall = $value WHERE relativecomplexity=:relativecomplexity AND isteacherownerofset=:isteacherownerofset AND ispopular=:ispopular AND actualitystate=:actualitystate AND idlearner=:idlearner AND idsubgroup=:idsubgroup")
									// 	->bindValues(array(
									// 		':relativecomplexity' => $relativecomplexity, 
									// 		':isteacherownerofset' => $flagTeacherOwner, 
									// 		':ispopular' => $flagPopular, 
									// 		':actualitystate' => $activeState, 
									// 		':idlearner' => $idLearnerY, 
									// 		':idsubgroup'=>$idSubgroup))
									// 	->execute();
									// }



									// Yii::app()->dbY->createCommand()
									// 	->update(
									// 		'setlearnerquestuion', // таблица
									// 		array('lagcountavailableall'=>$currentActiveState['LAG'],), // "поле" => значение
									// 		array(
									// 			'relativecomplexity=:relativecomplexity',// условие выборки
									// 			'isteacherownerofset=:isteacherownerofset',
									// 			'ispopular=:ispopular',
									// 			'actualitystate=:actualitystate',
									// 			'idlearner=:idlearner', 
									// 			'idsubgroup=:idsubgroup',
									// 			),
									// 		array(
									// 			'relativecomplexity'=>1, // значения для условия выборки
									// 			'isteacherownerofset'=>1,
									// 			'ispopular'=>1,
									// 			'actualitystate'=>1,
									// 			':idlearner'=>$idLearnerY,
									// 			':idsubgroup'=>$idSubgroup,
									// 			)
									// 	);
									// exit();	
								}
							}
						}
					}
				}
			}

		// ###### LAG (отстающие) ######
		// 	$countTPReviewLag = $countTP * $subgroupRatio->lagkreview;
		// 	print_r($countTPReviewLag." - TPReviewLAG<br>");
		// 	$countTPActLag = $countTP * $subgroupRatio->lagkactivestudyduration;
		// 	print_r($countTPActLag." - TPActLAG<br><br>");


		// ###### ACTUAL (нормальные) ######
		// 	$countTPReviewActual = $countTP * $subgroupRatio->actualkreview;
		// 	print_r($countTPReviewActual." - TPReviewACTUAL<br>");		
		// 	$countTPActActual = $countTP * $subgroupRatio->actualkactivestudyduration;
		// 	print_r($countTPActActual." - TPActACTUAL<br>");
		// 	$countTPSectionActual = $countTP * $subgroupRatio->actualkactivesection;
		// 	print_r($countTPSectionActual." - TPSectionACTUAL<br><br>");


		// ###### ADVANCE (экстерн) ######
		// 	$countTPReviewAdvance = $countTP * $subgroupRatio->advancekreview;
		// 	print_r($countTPReviewAdvance." - TPReviewADVANCE<br>");
		// 	$countTPActAdvance = $countTP * $subgroupRatio->advancekactivestudyduration;
		// 	print_r($countTPActAdvance." - TPActADVANCE<br>");
		// 	$countTPSectionAdvance = $countTP * $subgroupRatio->advancekactivesection;
		// 	print_r($countTPSectionAdvance." - TPSectionADVANCE<br>");
		// 	$countTPExternAdvance = $countTP * $subgroupRatio->advancekextern;
		// 	print_r($countTPExternAdvance." - TPExternADVANCE<br><br>");
                        
            return true;
	}

    /**
     * Пересчет множеств REGULAR
     * @access public
     * @param int idlearnerY
     * @ParamType idlearnerY int
     */
    static public function calculateCountQuestions($idlearnerY = 37){
        # Получаем l_level_id  ученика
        $leranerIdLevel = Yii::app()->dbY->createCommand()
            ->select('l_level_id')
            ->from('y_learner')
            ->where('id='.$idlearnerY)
            ->queryRow();

        # Получаем уровень ученика (number)
        $levelLearner = Yii::app()->dbY->createCommand()
            ->select('number')
            ->from('y_level')
            ->where('id='.$leranerIdLevel['l_level_id'])
            ->queryRow();

        # Получаем один любой splg_subgroup_id ученика
        $oneSubgroupLearner = Yii::app()->dbY->createCommand()
            ->select('splg_subgroup_id')
            ->from('y_splearnergroup')
            ->where('splg_learner_id='.$idlearnerY)
            ->limit(1)
            ->queryRow();

        # Получаем уровень подгруппы
        $levelSubgroup = Yii::app()->dbY->createCommand()
            ->select('level')
            ->from('y_subgroup')
            ->where('id='.$oneSubgroupLearner['splg_subgroup_id'])
            ->queryRow();

        # Получаем разницу между уровнем ученика и его подгруппой
        $levelDifference = $levelSubgroup['level'] - $levelLearner['number'];

        if($levelDifference > 2)
            $state = 'lagcountavailableall';

        if($levelDifference <= 2 && $levelDifference >=(-2))
            $state = 'actualcountavailableall';

        if($levelDifference < (-2))
            $state = 'advancecountavailableall';


        # Получаем все множества конкретного ученика (set (англ.) - множество, набор)
        $setsOfLearner = Yii::app()->dbY->createCommand()
            ->select('id')
            ->from('y_setlearnerquestion')
            ->where('slq_learner_id='.$idlearnerY)
            ->queryAll();

        # Цикл по всем множествам
        foreach($setsOfLearner as $oneSet => $value)
        {
            $massOfCountQuestions = array();

            # Цикл нахождения всех полей countall4-10 конкретного множества
            for($mark = 4; $mark < 11; $mark++)
            {
                $countAllQuestionEachMark = Yii::app()->dbY->createCommand()
                    ->select('count(*) as count')
                    ->from('y_splearnerquestion')
                    ->where('splq_setlearnerquestion_id= '.$value['id'].' AND level <= '.$levelLearner['number']. ' AND mark = '.$mark)
                    ->queryRow();
                $massOfCountQuestions['countall'.$mark] = $countAllQuestionEachMark['count'];
            }

            # Обновление данных countall4-10 у конкретного множества
            YSetlearnerquestion::model()->updateAll(
                $massOfCountQuestions,
                "id=:id",
                array(
                    ':id' => $value['id']));

            # Lag или Actual или Advance переменная, в зависимости от $state переменной
            $LActAdvcountAvaliableAll = Yii::app()->dbY->createCommand()
                ->select($state)
                ->from('y_setlearnerquestion')
                ->where('id = '.$value['id'])
                ->queryRow();

            # Получаем обновленные данные о countall4-10 конкретного множества
            $countAll4_10 =  Yii::app()->dbY->createCommand()
                ->select('countall4, countall5, countall6, countall7, countall8, countall9, countall10')
                ->from('y_setlearnerquestion')
                ->where('id = '.$value['id'])
                ->queryAll();

            # Присваиваем массиву данные для полей countavailable4-10
            for($mark = 4; $mark < 11; $mark++)
            {
                if($LActAdvcountAvaliableAll[$state] <= $countAll4_10[0]['countall'.$mark])
                    $massCountAvaliableQuestions['countavailable'.$mark] = $LActAdvcountAvaliableAll[$state];
                else
                if($LActAdvcountAvaliableAll[$state] > $countAll4_10[0]['countall'.$mark])
                    $massCountAvaliableQuestions['countavailable'.$mark] = $countAll4_10[0]['countall'.$mark];
            }

            # Обновляем поля countavailable
            YSetlearnerquestion::model()->updateAll(
                $massCountAvaliableQuestions,
                "id=:id",
                array(
                    ':id' => $value['id']));

        }



    }

    /**
     * Завершение подуровня
     * @access public
     * @param int $idLearner
     * @param int $idSublevel
     * @param array $idQuestYAnswDo
     * @ParamType $idLearner int
     * @ParamType $idSublevel int
     * @ParamType $idQuestYAnswDo array
     */
    static public function finishSubevelY($idLearner=37, $idSublevel, $idQuestYAnswDo){
        $idQuestYAnswDo = array(
                1 => array(
                    0 => 6,
//                    1 => 1
                ),

                2 => array(
                    0 => 12
                ),

                3 => array(
                    0 => 15
                ),

                4 => array(
                    0 => 20
                ),

                5 => array(
                    0 => 22
                )
            );

        /*
         * Результаты тестов - массив:
         *  [1](idQuestionY)=> array (
         *       [0]=> 1 or 0 (true of false)
         *  )
         */
        $resultOfTest = Test::analyzingFinishSublevel($idQuestYAnswDo);

        # Получаем idLevel
            $idLevel = Yii::app()->dbY->createCommand()
                ->select('sl_level_id')
                ->from('y_sublevel')
                ->where('id = '.$idSublevel)
                ->queryRow();

        foreach($resultOfTest as $idSetLearnerQuestionY => $trueFalse)
        {
            # Получаем оценку (стоимость) вопроса
                $questionMark =  Yii::app()->dbY->createCommand()
                    ->select('mark')
                    ->from('y_splearnerquestion')
                    ->where('id = '.$idSetLearnerQuestionY)
                    ->queryRow();

            # Если ответ на вопрос верный
                if($trueFalse)
                {
                    # Суммируем общее количество баллов
                        $summBall += $questionMark['mark'];
                    # Штраф оценке -2
                        $questionMark['mark'] = $questionMark['mark']-2;
                }
                else
                {
                    # Штраф оценке -1
                        $questionMark['mark'] = $questionMark['mark']-1;

                    # Получаем данные для дальнейшего внесения их в таблицу y_failanswer
                        $dataForFailAnswer =  Yii::app()->dbY->createCommand()
                            ->select('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, splq_questionmeta_id')
                            ->from('y_splearnerquestion')
                            ->where('id = '.$idSetLearnerQuestionY)
                            ->queryRow();

                    # Вставляем запись в таблицу y_failanswer
                        $fa = new YFailanswer();
                        $fa->idownerdo = $dataForFailAnswer['idownerdo'];
                        $fa->idmaterialdo = $dataForFailAnswer['idmaterialdo'];
                        $fa->idsectiondo = $dataForFailAnswer['idsectiondo'];
                        $fa->idparagraphdo = $dataForFailAnswer['idparagraphdo'];
                        $fa->idquestiondo = $dataForFailAnswer['idquestiondo'];
                        $fa->idsubjectdo = $dataForFailAnswer['idsubjectdo'];
                        $fa->fa_questionmeta_id = $dataForFailAnswer['splq_questionmeta_id'];
                        $fa->fa_level_id = $idLevel['sl_level_id'];
                        $fa->save(true);

    //                YFailanswer::model()->insert(
    //                    array('attributes'
    //                        'idownerdo'=>$dataForFailAnswer['idownerdo'],
    //                        'idmaterialdo'=>$dataForFailAnswer['idmaterialdo'],
    //                        'idsectiondo'=>$dataForFailAnswer['idsectiondo'],
    //                        'idparagraphdo'=>$dataForFailAnswer['idparagraphdo'],
    //                        'idquestiondo'=>$dataForFailAnswer['idquestiondo'],
    //                        'idsubjectdo'=>$dataForFailAnswer['idsubjectdo'],
    //                        'fa_questionmeta_id'=>$dataForFailAnswer['splq_questionmeta_id'],
    //                        'fa_level_id'=>$idLevel['sl_level_id']
    //                        )
    //                );
                }
            # Если стоимость вопроса < 4
                if($questionMark['mark'] < 4)
                    # Блокируем вопрос
                        YSplearnerquestion::model()->updateByPk(
                            $idSetLearnerQuestionY,
                            array('isblocked'=>1,'dateblock'=>date("Y-m-d"))
                        );

            # Обновляем оценку (стоимость) вопроса
                YSplearnerquestion::model()->updateByPk($idSetLearnerQuestionY, $questionMark);
        }
        # Обновляем оценку за подуровень
            YSublevel::model()->updateByPk($idSublevel, array('mark' => $summBall));

        # Если сумма набранных баллов > 30
            if($summBall >= 30)
            {
                # Добавляем 1 подарок
                    if($summBall >= 40)
                    {
                          YLevel::model()->updateCounters(
                              array('tryleft'=>1),
                              'id=:id',
                              array(':id'=>$idLevel['sl_level_id'])
                          );
                    }

                # Устанавдиваем дату финиша в таблице sublevel
                    YSublevel::model()->updateByPk($idSublevel,array('datefinish'=>date("Y-m-d")));

                # Получаем sublevelstatus из таблицы level
                    $sublevelStatus = Yii::app()->dbY->createCommand()
                        ->select('sublevelstatus')
                        ->from('y_level')
                        ->where('id = '.$idLevel['sl_level_id'])
                        ->queryRow();

                # Если статус подуровней (количество пройденных подуровней) <= 6 (нужно ли еще пройти)
                if($sublevelStatus['sublevelstatus'] <= 6)
                {
                    # Инкриментируем sublevelstatus
                        YLevel::model()->updateCounters(
                            array('sublevelstatus'=>1),
                            'id=:id',
                            array(':id'=>$idLevel['sl_level_id'])
                        );

                    /*
                     * НАЧАТЬ ПОДУРОВЕНЬ
                     *
                     * */
                }
                else
                {
                    /*
                     * ЗАВЕРШИТЬ ПОДУРОВЕНЬ
                     *
                     * */
                }
            }

        # Если сумма набранных баллов < 30
            else
            {
                # Обновляем дату обновления подвопроса
                    YSublevel::model()->updateByPk($idSublevel,array('daterefresh'=>date("Y-m-d")));

                # Получаем имеющееся количество попыток (подарков)
                    $tryleft = Yii::app()->dbY->createCommand()
                        ->select('tryleft')
                        ->from('y_level')
                        ->where('id = '.$idLevel['sl_level_id'])
                        ->queryRow();

                # Если количество попыток (подарков) > 0
                    if($tryleft['tryleft'] > 0)
                    {
                        # Дикрементируем количество попыток (подарков)
                            YLevel::model()->updateCounters(
                                array('tryleft'=>-1),
                                'id=:id',
                                array(':id'=>$idLevel['sl_level_id'])
                            );

                        /*
                         *
                         * НАЧАТЬ ПОДУРОВЕНЬ
                         *
                         * */
                    }
                # Если количество попыток (подарков) <= 0
                    else
                    {
                        # Устанавливаем sublevelstatus в 0
                            YLevel::model()->updateByPk($idLevel['sl_level_id'],array('sublevelstatus'=> 0));

                        /**
                         *
                         *
                         * ЗАВЕРШИТЬ УРОВЕНЬ
                         *
                         */
                    }
            }
    }

    /**
     * Завершение подуровня
     * @access public
     * @param int $idLearner
     * @param int $sublevelStatus
     * @param int $summBall
     * @param array $idLevel
     * @ParamType $idLearner int
     * @ParamType $sublevelStatus int
     * @ParamType $summBall int
     * @ParamType $idLevel array
     */
    static public function finishLevelY($idLearnerY = 37, $sublevelStatus = 6, $summBall = 34, $idLevel = array('sl_level_id' => 1)){
        if($sublevelStatus == 6 && $summBall > 30)
        {
            # Получаем все подуровни конкретного уровня
                $allSublevelLevel = Yii::app()->dbY->createCommand()
                    ->select('relativecomplexity, sl_level_id, daterefresh, datefinish, mark')
                    ->from('y_sublevel')
                    ->where('sl_level_id = '.$idLevel['sl_level_id'])
                    ->queryAll();

            # Вставляем записи о полученых выше пдуровнях в историю подуровней
                foreach($allSublevelLevel as $key => $value)
                {
                    # Вставляем запись в таблицу y_sublevelhistory
                    # ПОЛЕ datefinish НЕ ДОЛЖНО БЫТЬ ПУСТЫМ !!!!
                        $sh = new YSublevelhistory();
                        $sh->relativecomplexity = $value['relativecomplexity'];
                        $sh->slh_level_id = $value['sl_level_id'];
                        $sh->daterefresh = $value['daterefresh'];
                        $sh->datefinish = $value['datefinish'];
                        $sh->mark = $value['mark'];
                        $sh->save();
                }

            # Получаем mark только пройденных подуровней одного уровня
                $marksSublevelLevelFinishOnly = Yii::app()->dbY->createCommand()
                    ->select('mark')
                    ->from('y_sublevel')
                    ->where('sl_level_id = '.$idLevel['sl_level_id']. ' AND daterefresh IS NULL AND datefinish IS NOT NULL')
                    ->queryAll();

            # Суммируем все mark в fullmark
                foreach($marksSublevelLevelFinishOnly as $key => $massMark)
                    $fullMark += $massMark['mark'];

            # Обновляем поле fullmark для уровня
                YLevel::model()->updateByPk($idLevel['sl_level_id'],array('fullmark'=> $fullMark));

            # Обновление количества попыток прохождения уровня
                YLevel::model()->updateCounters(
                    array('tries'=>1),
                    'id=:id',
                    array(':id'=>$idLevel['sl_level_id'])
                );

            # Устанавливаем datefinish у Level
                YLevel::model()->updateByPk($idLevel['sl_level_id'], array('datefinish'=>date("Y-m-d")));

            # Получаем number уровня
                $numberLevel = Yii::app()->dbY->createCommand()
                    ->select('number')
                    ->from('y_level')
                    ->where('id = '.$idLevel['sl_level_id'])
                    ->queryRow();

            # Вставляем новую запись с новым уровнем для ученика, с number больше на 1
                $level = new YLevel();
                $level->number = $numberLevel['number']+1;
                $level->datefirstcreate = date("Y-m-d");
                $level->datelastcreate = date("Y-m-d");
                $level->levelavailable = 0;
                $level->save();


            # Устанавливаем datefinish у Level
                YLevel::model()->updateByPk($idLevel['sl_level_id'], array('datefinish'=>date("Y-m-d")));

            # Обновляем idLevel в таблице Learner для ученика
                YLearner::model()->updateByPk($idLearnerY, array('l_level_id'=>$level->id));

            # Пересчет множеств REGULAR
                LevelManager::calculateCountQuestions($idLearnerY);

            /**
             *
             * СФОРМИРОВАТЬ УРОВЕНЬ
             *
             */
        }
        else
        {
            # Удаляем все подуровни для текущего уровня (idLevel)
                YSublevel::model()->deleteAll("sl_level_id=:idLevel", array(':idLevel'=>$idLevel['sl_level_id']));

            # Обновление количества попыток прохождения уровня
                YLevel::model()->updateCounters(
                    array('tries'=>1),
                    'id=:id',
                    array(':id'=>$idLevel['sl_level_id'])
                );
        }

    }


    static public function createLevel(){
       /**
        *
        * АНАЛИЗ ДОСТАТОЧНОСТИ ВОПРОСОВ
        *
        */
        $analiz = 1;
        if($analiz == 1)
        {

        }
        else
        {
            return "Сформировать уровень невозможно";
        }
    }
    /**
     * Метод создает множества для определенного ученика в определенной группе
     * @param integer $idLearnerY ID ученика из Y
     * @param integer $idSubgroupY ID учебной группы из Y
     */
    public static function createLearnerSetsInSubgroup($idLearnerY, $idSubgroupY){
        $countCreated = 0;
        $command = new CDbCommand(Yii::app()->dbY);
        # Получить ID предмета по идентификатору группы
        $tempArray = $command->select("idsubjectdo, absolutecomplexity")
                ->from("y_subgroup")
                ->where("id=:idsubgroupy", array(":idsubgroupy" => $idSubgroupY))
                ->queryRow();
        $IDsubjectDO = $tempArray["idsubjectdo"];
        $absolutecomplexity = $tempArray["absolutecomplexity"];
        for($relcomplexity = 1; $relcomplexity <= 3; $relcomplexity++){
            for($actstate = 1; $actstate <= 4; $actstate++){
                for($ispopular = 0; $ispopular <= 1; $ispopular++){
                    for($isborrowed = 0; $isborrowed <= 1; $isborrowed++){
                        $setLearnerQuestionObject = new YSetlearnerquestion();
                        $setLearnerQuestionObject->relativecomplexity  = $relcomplexity;
                        $setLearnerQuestionObject->absolutecomplexity  = $absolutecomplexity;
                        $setLearnerQuestionObject->actualitystate = $actstate;
                        $setLearnerQuestionObject->slq_learner_id = $idLearnerY;
                        $setLearnerQuestionObject->slq_subgroup_id = $idSubgroupY;
                        $setLearnerQuestionObject->idsubjectdo = $IDsubjectDO;
                        $setLearnerQuestionObject->ispopular = $ispopular;
                        $setLearnerQuestionObject->isteacherownerofset = $isborrowed;
                        if($setLearnerQuestionObject->save()){
                            $countCreated++;
                        }
                    }
                }
            }
        }
        return $countCreated;
    }
}
?>