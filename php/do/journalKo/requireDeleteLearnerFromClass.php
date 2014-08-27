<?php

    if(isset($idLearner) && isset($idSchool) && isset($idClass))
    {
        $deleteOneLearner = $DB_DO->query("CALL dLearnerOne(?i, ?i, ?i)",$idLearner, $idClass, $idSchool);
        $deleteUserFromRolesTable = $DB->query(QS::$dRole, $idSchool, $idLearner, 1);
        
        // delete learner FROM all subgroups IN Y
        $IDlearnerY = $DB_Y->getOne(QSY::$getLearnerYIDFromLearnerDOID, $idLearner);
        $r = $DB_Y->query(QSY::$deleteLearner, $IDlearnerY);
//        $subgroupsClassFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idClass);
//        foreach ($subgroupsClassFromDB as $subgroupClassFromDB) {
//            $IDsubgroupY = $DB_Y->getOne(QSY::$getSubgroupYIDFromSubgroupDOID, $subgroupClassFromDB["id"]);
//            $r = $DB_Y->query(QSY::$deleteLearnerFromGroup, $IDlearnerY, $IDsubgroupY);
//        }
        
    }

        if($deleteOneLearner && $deleteUserFromRolesTable)
        {
            foreach($massResponsible as $key => $idResponsible)
            {
                $decrementCountLearner = $DB_DO->query(QSDO::$decrementCountLearner, $idResponsible);

                $countLearnersInResponsibleTable = $DB_DO->getOne(QSDO::$getCountLearnersInResponsibleTable, $idResponsible);
                if($countLearnersInResponsibleTable == 0)
                {
                    $deleteUserFromResponsibleTable = $DB_DO->query(QSDO::$deleteUserFromResponsibleTable, $idResponsible);
                    
                    $deleteUserFromRolesTable = $DB->query(QS::$deleteUserFromRolesTable, $idResponsible, 8);
                }

            }
        }

?>