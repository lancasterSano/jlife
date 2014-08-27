<?php
Yii::import('application.components.evolution.subsidiaryNightScenario.*');
/**
* 2 Sum data of previous day
* 2 Подвести итоги за прошлые сутки
*/
class SummingDataPreviousDay extends LogManager
{
    public $finish = null;

    function __construct()
    {
        $this->run();
        $this->finish = true;
    }
    public function run(){
        # Пересчитать countrightanswer , countallanswer и answeractivity
//        $command = new CDbCommand(SPLearnerquestion::model()->getDbConnection());
//        $command->select("id")
//                ->from("questionmeta");
//        $arrayQuestionmetaID = $command->queryColumn();
//        foreach ($arrayQuestionmetaID as $IDQuestionmeta) {
//            $command->reset();
//            $command->select("SUM(countrightanswer) AS sumcountrightanswer, SUM(countallanswer) AS sumcountallanswer");
//            $command->from("spisoklearnerquestion");
//            $command->where("idquestionmeta=:idqm", array(":idqm"=>$IDQuestionmeta));
//            $sum = $command->queryRow();
//            $sumcountrightanswer = $sum["sumcountrightanswer"];
//            $sumcountallanswer = $sum["sumcountallanswer"];
//            $answeractivity = $sumcountrightanswer/$sumcountallanswer;
//            $Questionmeta = Questionmeta::model()->findAllByPk($IDQuestionmeta);
//            $Questionmeta->countrightanswer = $sumcountrightanswer;
//            $Questionmeta->countallanswer = $sumcountallanswer;
//            $Questionmeta->answeractivity = $answeractivity;
//            if($Questionmeta->relativecomplexity == 1){
//                if($answeractivity < $Yii::app()->params['evolution']['increasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Средний"
//                    $Questionmeta->relativecomplexity = 2;
//                    # нужно добавить новый questionmeta с absolutecomplexity++, если она не превышает 12
//                    $newabsolutecomplexity = intval($Questionmeta->absolutecomplexity) + 1;
//                    $countquestionmetanewac = Questionmeta::model()->count("idquestiontext=:idqt AND absolutecomplexity=:ac", array(":idqt"=>$Questionmeta->idquestiontext, ":ac"=>$newabsolutecomplexity));
//                    if($newabsolutecomplexity <= 12 && !$countquestionmetanewac){
//                        $newQuestionmeta = new Questionmeta();
//                        $newQuestionmeta->idownerdo = $Questionmeta->idownerdo;
//                        $newQuestionmeta->idmaterialdo = $Questionmeta->idmaterialdo;
//                        $newQuestionmeta->idsectiondo = $Questionmeta->idsectiondo;
//                        $newQuestionmeta->idparagraphdo = $Questionmeta->idparagraphdo;
//                        $newQuestionmeta->idquestiontext = $Questionmeta->idquestiontext;
//                        $newQuestionmeta->relativecomplexity = 1;
//                        $newQuestionmeta->absolutecomplexity = $newabsolutecomplexity;
//                        $newQuestionmeta->idquestiontext = $Questionmeta->idquestiontext;
//                 if($answeractivity > $Yii::app()->params['evolution']['easycomplexitylimittoblock']){
//                    # заблокировать вопрос
//                    $Questionmeta->dateblock = date("Y-m-d H:i:s");
//                }
//            }
//            if($Questionmeta->relativecomplexity == 2){
//                if($answeractivity < $Yii::app()->params['evolution']['increasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Трудный"
//                    $Questionmeta->relativecomplexity = 3;
//                }
//                if($answeractivity > $Yii::app()->params['evolution']['decreasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Легкий"
//                    $Questionmeta->relativecomplexity = 1;
//                }
//            }
//            if($Questionmeta->relativecomplexity == 3){
//                if($answeractivity < $Yii::app()->params['evolution']['hardcomplexitylimittoblock']){
//                    # Заблокировать вопрос
//                    $Questionmeta->dateblock = date("Y-m-d H:i:s");
//                }
//                if($answeractivity > $Yii::app()->params['evolution']['decreasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Средний"
//                    $Questionmeta->relativecomplexity = 2;
//                    # нужно добавить новый questionmeta с absolutecomplexity--, если она больше 0
//                    $newabsolutecomplexity = intval($Questionmeta->absolutecomplexity) - 1;
//                    $countquestionmetanewac = Questionmeta::model()->count("idquestiontext=:idqt AND absolutecomplexity=:ac", array(":idqt"=>$Questionmeta->idquestiontext, ":ac"=>$newabsolutecomplexity));
//                     if($newabsolutecomplexity > 0 && !$countquestionmetanewac){
//                        $newQuestionmeta = new Questionmeta();
//                        $newQuestionmeta->idownerdo = $Questionmeta->idownerdo;
//                        $newQuestionmeta->idmaterialdo = $Questionmeta->idmaterialdo;
//                        $newQuestionmeta->idsectiondo = $Questionmeta->idsectiondo;
//                        $newQuestionmeta->idparagraphdo = $Questionmeta->idparagraphdo;
//                        $newQuestionmeta->idquestiontext = $Questionmeta->idquestiontext;
//                        $newQuestionmeta->relativecomplexity = 3;
//                        $newQuestionmeta->absolutecomplexity = $newabsolutecomplexity;
//                        $newQuestionmeta->idquestiontext = $Questionmeta->idquestiontext;
//                        $newQuestionmeta->save();
//                    }
//                }
//            }
//            $Questionmeta->save();
//        }
//        # Обновить spisoklearnerquestion по новым questionmeta, установить idsetlearnerquestion в null
//        $command->reset();
//        $command->select("id");
//        $command->from("spisoklearnerquestion");
//        $arraySpisoklearnerquestionID = $command->queryColumn();
//        foreach($arraySpisoklearnerquestionID as $IDspisoklearnerquestion){
//            $spisoklearnerquestion = SPLearnerquestion::model()->findAllByPk($IDspisoklearnerquestion);
//            $Questionmeta = Questionmeta::model()->findAllByPk($spisoklearnerquestion["idquestionmeta"]);
//            $spisoklearnerquestion->absolutecomplexity = $Questionmeta->absolutecomplexity;
//            $spisoklearnerquestion->dateblock = $Questionmeta->dateblock;
//            $spisoklearnerquestion->relativecomplexity = $Questionmeta->relativecomplexity;
//            $spisoklearnerquestion->idsetlearnerquestion = NULL;
//            $spisoklearnerquestion->save();
//        }                  $newQuestionmeta->save();
//                    }
//                }
//                if($answeractivity > $Yii::app()->params['evolution']['easycomplexitylimittoblock']){
//                    # заблокировать вопрос
//                    $Questionmeta->dateblock = date("Y-m-d H:i:s");
//                }
//            }
//            if($Questionmeta->relativecomplexity == 2){
//                if($answeractivity < $Yii::app()->params['evolution']['increasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Трудный"
//                    $Questionmeta->relativecomplexity = 3;
//                }
//                if($answeractivity > $Yii::app()->params['evolution']['decreasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Легкий"
//                    $Questionmeta->relativecomplexity = 1;
//                }
//            }
//            if($Questionmeta->relativecomplexity == 3){
//                if($answeractivity < $Yii::app()->params['evolution']['hardcomplexitylimittoblock']){
//                    # Заблокировать вопрос
//                    $Questionmeta->dateblock = date("Y-m-d H:i:s");
//                }
//                if($answeractivity > $Yii::app()->params['evolution']['decreasecomplexitylimit']){
//                    # вопрос должен перейти в градацию "Средний"
//                    $Questionmeta->relativecomplexity = 2;
//                    # нужно добавить новый questionmeta с absolutecomplexity--, если она больше 0
//                    $newabsolutecomplexity = intval($Questionmeta->absolutecomplexity) - 1;
//                    $countquestionmetanewac = Questionmeta::model()->count("idquestiontext=:idqt AND absolutecomplexity=:ac", array(":idqt"=>$Questionmeta->idquestiontext, ":ac"=>$newabsolutecomplexity));
//                     if($newabsolutecomplexity > 0 && !$countquestionmetanewac){
//                        $newQuestionmeta = new Questionmeta();
//                        $newQuestionmeta->idownerdo = $Questionmeta->idownerdo;
//                        $newQuestionmeta->idmaterialdo = $Questionmeta->idmaterialdo;
//                        $newQuestionmeta->idsectiondo = $Questionmeta->idsectiondo;
//                        $newQuestionmeta->idparagraphdo = $Questionmeta->idparagraphdo;
//                        $newQuestionmeta->idquestiontext = $Questionmeta->idquestiontext;
//                        $newQuestionmeta->relativecomplexity = 3;
//                        $newQuestionmeta->absolutecomplexity = $newabsolutecomplexity;
//                        $newQuestionmeta->idquestiontext = $Questionmeta->idquestiontext;
//                        $newQuestionmeta->save();
//                    }
//                }
//            }
//            $Questionmeta->save();
//        }
//        # Обновить spisoklearnerquestion по новым questionmeta, установить idsetlearnerquestion в null
//        $command->reset();
//        $command->select("id");
//        $command->from("spisoklearnerquestion");
//        $arraySpisoklearnerquestionID = $command->queryColumn();
//        foreach($arraySpisoklearnerquestionID as $IDspisoklearnerquestion){
//            $spisoklearnerquestion = SPLearnerquestion::model()->findAllByPk($IDspisoklearnerquestion);
//            $Questionmeta = Questionmeta::model()->findAllByPk($spisoklearnerquestion["idquestionmeta"]);
//            $spisoklearnerquestion->absolutecomplexity = $Questionmeta->absolutecomplexity;
//            $spisoklearnerquestion->dateblock = $Questionmeta->dateblock;
//            $spisoklearnerquestion->relativecomplexity = $Questionmeta->relativecomplexity;
//            $spisoklearnerquestion->idsetlearnerquestion = NULL;
//            $spisoklearnerquestion->save();
//        }
    }
}
?>