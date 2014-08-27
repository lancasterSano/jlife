<?php
Yii::import('application.components.evolution.subsidiaryNightScenario.*');
/**
* 5 Update sets
* 5 Обновление множеств
*/
class UpdateSets extends LogManager
{
    public $finish = null;

    function __construct()
    {
        $this->run();
        $this->finish = true;
    }
    public function run(){
        $command = new CDbCommand(Yii::app()->dbY);
        # 1. Определить вторичные ключи для вопросов, у которых NULL
        $arraySplquestionObject = YSPLearnerquestion::model()->findAll();
        foreach ($arraySplquestionObject as $splquestionObject) {
            $relativecomplexity = $splquestionObject->relativecomplexity;
            $absolutecomplexity = $splquestionObject->absolutecomplexity;
            $isborrowed = $splquestionObject->isborrowed;
            $countAllAnswer = $command->select("countallanswer")
                    ->from("y_questionmeta")
                    ->where("id=:idqm", array(":idqm" => $splquestionObject->splq_questionmeta_id))
                    ->queryScalar();
            if($countAllAnswer > $Yii::app()->params['evolution']['countallanswertobepopular'])
                $ispopular = 1;
            else
                $ispopular = 0;
            $command->reset();
            $tmpArray = $command->select("numbersectiondo, date")
                    ->from("y_sectiontiming")
                    ->where("st_subgroup_id=:idsg AND idsectiondo=:idsc", array(":idsg" => $splquestionObject->splq_subgroup_id, ":idsc" => $splquestionObject->idsectiondo))
                    ->queryRow();
            $dateOpenCurrentSection = strtotime($tmpArray["date"]);
            $numbernextsection = intval($tmpArray["numbersectiondo"])+1;
            $command->reset();
            $dateOpenNextSection = strtotime($command->select("date")
                    ->from("y_sectiontiming")
                    ->where("st_subgroup_id=:idsg AND numbersectiondo=:nm", array(":idsg" => $splquestionObject->splq_subgroup_id, ":nm" => $numbernextsection))
                    ->queryScalar());
            $curDate = strtotime(date());
            if($curDate < $dateOpenCurrentSection){
                $actualityState = 3;
            } else if ($curDate > $dateOpenCurrentSection){
                if($curDate < $dateOpenNextSection){
                    $actualityState = 2;
                } else {
                    $actualityState = 1;
                }
            }
            $command->reset();
            $idsetlearnerquestion = $command->select("id")
                    ->from("y_setlearnerquestion")
                    ->where("relativecomplexity=:rc AND absolutecomplexity=:ac AND
                        isteacherownerofset=:isb AND ispopular=:isp AND actualitystate=:acs
                        AND slq_learner_id=:idl AND sql_subgroup_id=:ids",
                            array(":rc" => $relativecomplexity,
                                ":ac" => $absolutecomplexity,
                                ":isb" => $isborrowed,
                                ":isp" => $ispopular,
                                ":acs" => $actualityState,
                                ":idl" => $splquestionObject->splq_learner_id,
                                ":ids" => $splquestionObject->splq_subgroup_id))
                    ->queryScalar();
            
        }
    }
}
?>