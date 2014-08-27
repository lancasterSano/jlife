<?php
Yii::import('application.components.evolution.subsidiaryNightScenario.*');
/**
* 3 Add missing data
* 3 Избавиться от данных, которые не будут нужны
*/
class AddMissingData extends LogManager
{
    public $finish = null;

    function __construct()
    {
        $this->run();
        $this->finish = true;
    }
    public function run(){
        # 1. Добавить новых и переведенных учеников в группы
        # Выбрать учеников, которые являются новыми либо переведенными
        $command = new CDbCommand(Yii::app()->dbY);
        echo "1. SPLearnergroup where setcreated = 0 and deleted = 0".PHP_EOL;
        $arraySplearnergroup = YSPLearnergroup::model()->findAll("setcreated = 0 AND deleted = 0");
        foreach ($arraySplearnergroup as $spLearnergroup) {
            echo "SUBGROUP - ".$spLearnergroup->splg_subgroup_id." LEARNER - ".$spLearnergroup->splg_learner_id.PHP_EOL;
            echo "Creating sets...".PHP_EOL;
            # 1.1. Создать множества для ученика
            $countCreated = LevelManager::createLearnerSetsInSubgroup($spLearnergroup->splg_learner_id, $spLearnergroup->splg_subgroup_id);
            echo "Created ".$countCreated."/48 sets".PHP_EOL;
            # 1.2. Пересчитать множества для текущего ученика
            if($countCreated == 48){
                if(LevelManager::recalculateAvailableQuestions(array($spLearnergroup->splg_subgroup_id), $spLearnergroup->splg_learner_id)){
                    $rowsUpdated = YSPLearnergroup::model()->updateByPk($spLearnergroup->id, array("setcreated" => 1));
                }
            }

            echo "2. Delete old spisoklearnergroup (deleted = 1)".PHP_EOL;
            # 1.3. Удалить записи, где deleted = 1 (переведенные ученики)
            $command->reset();
            $IDsubjectDO = $command->select("idsubjectdo")
                    ->from("y_subgroup")
                    ->where("id=:ids", array(":ids" => $spLearnergroup->splg_subgroup_id))
                    ->queryScalar();
            $arraySplgroupToDel = $command->reset()
                    ->select("splg.id")
                    ->from("y_splearnergroup splg")
                    ->join("y_subgroup sb", "sb.id = splg.splg_subgroup_id")
                    ->where("splg_learner_id=:idl AND deleted = 1 AND idsubjectdo=:ids", array(":idl"=>$spLearnergroup->splg_learner_id, ":ids" => $IDsubjectDO))
                    ->queryColumn();
            echo "IDSubjectDO - ".$IDsubjectDO.PHP_EOL;
            echo "Old subgroups (transfer) ID Array - [".  implode(",", $arraySplgroupToDel)."]".PHP_EOL;
            YSPLearnergroup::model()->deleteByPk($arraySplgroupToDel);
            echo PHP_EOL;
        }
        # 2. Проверить subgroupratio для всех групп (пока не используется, может в будущем будет)
        # 3. Проверить и перевести классы на новый уровень (в процессе)
    }
}
?>