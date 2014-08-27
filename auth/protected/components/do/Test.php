<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 23.04.14
 * Time: 12:11
 */

class Test {
    static public function analyzingFinishSublevel($idQuestYAnswDo){
//      $idQuestYAnswDo = json_encode($idQuestYAnswDo);

        # Циакл по нахождению idquestiondo при помощи idQuestionY
        foreach($idQuestYAnswDo as $idQuestion => $massAnswers)
        {
            $idQuestDo =  Yii::app()->dbY->createCommand()
                ->select('idquestiondo')
                ->from('y_splearnerquestion')
                ->where('id = '.$idQuestion)
                ->queryRow();
            # Формирование массива idQuestionY => idQuestionDo, который поможет в дальнейшей конвертации
            $idQuestYidQuestDo[$idQuestion] = $idQuestDo['idquestiondo'];

            # Формирование масива idQuestionDo => массив ответов
            $idQuestDoAnswDo[$idQuestDo['idquestiondo']] = $massAnswers;
        }

        # Формирование результатов тестирование, которые необходимо конвертировать к соответствующим idQuestionY
        $resultAnswers = self::finishSublevelDo($idQuestDoAnswDo);

        # Замена idQuestionDo => trueFalse на idQuestionY => trueFalse
        foreach($resultAnswers as $idQuestDo => $trueFalse)
            $resultAnswersConvert[array_search($idQuestDo, $idQuestYidQuestDo)] = $trueFalse;

        return $resultAnswersConvert;
    }

    static public function finishSublevelDo($idQuestDoAnswDo){

        # Формирование массива idQuestionsDo
        foreach($idQuestDoAnswDo as $idQuestion => $massUserAnswers)
            $idQuestions[] = $idQuestion;

        # Получаем массив правилных ответов из таблицы DOAnswers
        $criteria = new CDbCriteria();
        $criteria->condition = 't.right = :right';
        $criteria->params = array(':right' => 1);
        $criteria->addInCondition('questionS_id1', $idQuestions);

        $correctAnswers = DOAnswers::model()->findAll($criteria);


            foreach ($correctAnswers as $key => $value)
            {
                $idQuestion = $value->attributes['questionS_id1'];
                $idAnswer = $value->attributes['id'];
                $results[$idQuestion][$idAnswer] = (string)$idAnswer;
            }

            foreach ($results as $idQuestion => $massRightAnswers)
            {
                $count = 0;
                $corrAnsw = true;
                foreach ($massRightAnswers as $idAnswerKey => $idAnswer) {
                    if( $idAnswer != $idQuestDoAnswDo[$idQuestion][$count])
                    {
                        $corrAnsw = false;
                    }
                    $count++;
                }

                if ($corrAnsw == true && count($massRightAnswers) == count($idQuestDoAnswDo[$idQuestion]))
                    $answer = 1;
                else
                    $answer = 0;

                $finalResults[$idQuestion] = $answer;

            }
        return $finalResults;
    }
} 