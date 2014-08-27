<?php

        $deleteFromSpisokResponsibleLearnerTable = $DB_DO->query(QSDO::$deleteFromSpisokResponsibleLearnerTable, $idResponsible, $idLearner);
        
        if($deleteFromSpisokResponsibleLearnerTable)
            $decrementCountLearner = $DB_DO->query(QSDO::$decrementCountLearner, $idResponsible);

        $countLearnersInResponsibleTable = $DB_DO->getOne(QSDO::$getCountLearnersInResponsibleTable, $idResponsible);

        if($countLearnersInResponsibleTable == 0)
        {
            $deleteUserFromResponsibleTable = $DB_DO->query(QSDO::$deleteUserFromResponsibleTable, $idResponsible);
                    
            $deleteUserFromRolesTable = $DB->query(QS::$deleteUserFromRolesTable, $idResponsible, 8);
        }

?>