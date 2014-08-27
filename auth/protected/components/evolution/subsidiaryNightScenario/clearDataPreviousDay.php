<?php
Yii::import('application.components.evolution.subsidiaryNightScenario.*');
/**
* 1 Clear data previous day
* 1 Избавиться от данных, которые не будут нужны
*/
class ClearDataPreviousDay extends LogManager
{
    public $finish = null;

    function __construct()
    {
        $this->run();
        $this->finish = true;
    }
    public function run(){
        $command = new CDbCommand(Yii::app()->dbY);
        
        # 1. Полная очистка таблицы sublevel
        $numRowsDeleted = YSublevel::model()->deleteAll();
        
        # 2. Проверка level'ов учеников
        # 2.1 Увеличение счетчиков в level, где sublevelstatus > 0
        $numRowsUpdatedTries = YLevel::model()->updateCounters(array("tries" => 1), "sublevelstatus > 0");
        # 2.2 Сброс статуса в уровне
        $numRowsUpdatedStatus = YLevel::model()->updateAll(array("sublevelstatus" => 0), "sublevelstatus > 0");
        # 2.3 Возврат количества оставшихся попыток в дефолтное состояние
        $numRowsUpdatedTryleft = YLevel::model()->updateAll(array("tryleft" => Yii::app()->params["evolution"]["defaulttryleft"]));

        echo "---------------------------------------------------------------------".PHP_EOL
                ."3. Delete questions, where deleted in y_questionforgroup = 1".PHP_EOL;
        # 3. Удаление вопросов, у которых deleted в questionforgroup == true
        $arrayQuestionforgroupIDToDel = $command->select("id")
                ->from("y_questionforgroup")
                ->where("deleted = true")
                ->queryColumn();
        $log31 = "Questionforgroup ID Array WHERE deleted = 1 - [".implode(", ", $arrayQuestionforgroupIDToDel)."]";
        echo $log31.PHP_EOL;
        NightScenarioStruct::_log(31, $log31);

        $command->reset();
        $command->distinct = true;
        $arrayQuestionMetaID = $command->select("qfg_questionmeta_id")
                ->from("y_questionforgroup")
                ->where(array("in", "id", $arrayQuestionforgroupIDToDel))
                ->queryColumn();
        $log32 = "DISTINCT QuestionMeta ID Array WHERE id IN (".implode(", ", $arrayQuestionforgroupIDToDel)
                .") - [".implode(", ", $arrayQuestionMetaID)."]";
        echo $log32.PHP_EOL;
        NightScenarioStruct::_log(32, $log32);
        
        $command->reset();
        $command->distinct = true;
        $arraySubgroupID = $command->select("qfg_subgroup_id")
                ->from("y_questionforgroup")
                ->where(array("in", "id", $arrayQuestionforgroupIDToDel))
                ->queryColumn();
        $log33 = "DISTINCT Subgroup ID Array WHERE id IN (".
                implode(", ", $arrayQuestionforgroupIDToDel).") - [".implode(", ", $arraySubgroupID)."]";
        echo $log33.PHP_EOL;
        NightScenarioStruct::_log(33, $log33);
        echo PHP_EOL;

        foreach ($arraySubgroupID as $subgroupID) {
            echo "================= SUBGROUP # ".$subgroupID." ===========================".PHP_EOL;
            $command->reset();
            $arrayLearnerID = $command->select("splg_learner_id")
                    ->from("y_splearnergroup")
                    ->where("splg_subgroup_id=:ids", array(":ids"=>$subgroupID))
                    ->queryColumn();
            $log34 = "| Learner ID Array - [".implode(", ", $arrayLearnerID)."]";
            echo $log34.PHP_EOL."|".PHP_EOL;
            NightScenarioStruct::_log(34, $log34);
            foreach($arrayLearnerID as $learnerID){
                echo "|\t==== LEARNER #".$learnerID."===================================".PHP_EOL;
                QuestionManager::deleteLearnerQuestionsAndHistoryByQuestionMeta($arrayQuestionMetaID, $learnerID);
                echo "|\t==================================================".PHP_EOL."|".PHP_EOL;
            }
            echo "==========================================================".PHP_EOL.PHP_EOL;
        }
        QuestionManager::deleteQuestionsGroup($arrayQuestionforgroupIDToDel);
        
        echo "---------------------------------------------".PHP_EOL.
                "4. End study learners in groups".PHP_EOL;
        # 4. Завершение обучения учеников в группе
        $command->reset();
        $arraySpisokLearnerGroup = $command->select("*")
                ->from("y_splearnergroup")
                ->where("deleted = 1")
                ->queryAll();
        $learners = array();
        foreach ($arraySpisokLearnerGroup as $spisoklearnergroup) {
            $subgroup = YSubgroup::model()->findByPk($spisoklearnergroup["splg_subgroup_id"]);
            $learners[$spisoklearnergroup["splg_learner_id"]][$subgroup->idsubjectdo][] = $spisoklearnergroup;
        }
//        
        foreach ($learners as $idlearner => $learnersubjects) {
            echo "==== LEARNER #".$idlearner."===================================".PHP_EOL;
            foreach($learnersubjects as $idsubject => $spisoklearnergroups){
//                # Перенести вопросы ученика по данному предмету в историю
                echo "  1. Transfer questions of subject #".$idsubject." to history".PHP_EOL;
                $command->reset();
                $command->select("id")
                        ->from("y_splearnerquestion")
                        ->where("splq_learner_id=:idl AND idsubjectdo=:ids", array(":idl" => $idlearner, ":ids" => $idsubject));
                $splearnerquestionsToMove = $command->queryColumn();
                echo "  ID Array SPLearnerquestion to move - [".  implode(",", $splearnerquestionsToMove)."]".PHP_EOL;
                $resultArray = QuestionManager::moveLearnerQuestionsToHistory($splearnerquestionsToMove);
                foreach ($resultArray as $IDsplearnerquestion => $splearnerquestioninfo) {
                    echo "Spisoklearnerquestion #".$IDsplearnerquestion.": Moved - ".$splearnerquestioninfo["isMovedToHistory"].
                            ".Deleted - ".$splearnerquestioninfo["isDeleted"].PHP_EOL;
                }
                
                foreach($spisoklearnergroups as $spisoklearnergroup){
                    echo "  Subgroup #".$spisoklearnergroup["splg_subgroup_id"]." deleting sets...".PHP_EOL;
                    $command->reset();
                    $arraySetToDel = $command->select("id")
                            ->from("y_setlearnerquestion")
                            ->where("slq_learner_id=:idLearner AND slq_subgroup_id=:idSubgroup", 
                                    array(":idLearner" => $idlearner, ":idSubgroup" => $spisoklearnergroup["splg_subgroup_id"]))
                            ->queryColumn();
                    if($arraySetToDel){
                        echo "  Array ID sets to delete(".  count($arraySetToDel).") - [".  implode(",", $arraySetToDel)."]".PHP_EOL;
                        $numRowsSetsDeleted = YSetlearnerquestion::model()->deleteByPk($arraySetToDel);
                        echo "  Deleted ".$numRowsSetsDeleted." rows".PHP_EOL;
                        $command->reset();
                        $command->select("COUNT(*) as count")
                                ->join("y_subgroup", "y_subgroup.id = y_splearnergroup.splg_subgroup_id")
                                ->from("y_splearnergroup")
                                ->where("splg_learner_id=:idl AND deleted = 0 AND idsubjectdo=:ids", array(":idl"=>$idlearner, ":ids" => $idsubject));
                        $countTransfers = $command->queryScalar();
                        if($countTransfers == 0){
                            echo "  Count transfers = 0, so kill the record".PHP_EOL;
                            YSPLearnergroup::model()->deleteByPk($spisoklearnergroup["id"]);
                        } else {
                            echo "  Count transfers > 0, so leave the record".PHP_EOL;
                        }
                    } else {
                        echo "No sets to delete".PHP_EOL;
                    }
                }
            }
              echo "==================================================".PHP_EOL;
        }
    }
}
?>