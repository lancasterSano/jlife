<?php
	$learnerList = ClassS::getLearnersClassSTATIC($idClass);
            
            $i = 0;
            foreach($learnerList as $idLearner => $value)
            {
                $parentLearnerlist[++$i] = array(
                                'idProfile' => $value->idProfile,
                                'idLearner' => $idLearner,
                                'fio' => $value->FIO(),
                                'responsibles' => array()
                                );
                
                $pOneLearner = $DB_DO->getAll(QSDO::$getParentsOneLearner, $idLearner);
                
                if(!empty($pOneLearner))
                    foreach($pOneLearner as $key => $value)
                    {
                        $parentObject = new Responsible($value['id']);
                        $parentsOneLearner[$value['id']]['id'] = $value['id'];
                        $parentsOneLearner[$value['id']]['idProfile'] = $parentObject->idProfile;
                        $parentsOneLearner[$value['id']]['fio'] = $value['fio'];
                    }
                else
                    $parentsOneLearner['error'] = 'Нет родителя';
                
                $parentLearnerlist[$i]['responsibles'] = $parentsOneLearner;
                
                unset($parentsOneLearner);
            }
?>