<?php

/**
 * @access public
 * @author CHERRY
 */
class QuestionManager {

	/**
	 * add specified question to specified material
	 * @access public
	 * @param int idmaterialdo
	 * @param int idsection
	 * @param int idparagraph
	 * @param int idquestiondo
	 * @param int idowner
	 * @param int relativecomplexity
	 * @param int absolutecomplexity
	 * @ParamType idmaterialdo int
	 * @ParamType idsection int
	 * @ParamType idparagraph int
	 * @ParamType idquestiondo int
	 * @ParamType idowner int
	 * @ParamType relativecomplexity int
	 * @ParamType absolutecomplexity int
	 */
	public function addQuestionMaterial($idmaterialdo, $idsection, $idparagraph, $idquestiondo, $idowner, $relativecomplexity, $absolutecomplexity) {
		throw new Exception("Not yet implemented");
	}
	public function addQuestionMaterial1() {
		throw new Exception("Not yet implemented");
	}

	/**
	 * add question to teacher's favorite questions
	 * @access public
         * @param int idquestiontext
         * @param int idteacher
         * 
	 */
	public function addQuestionFavorite($_idquestiontext, $_idteacherdo) {
            $teacherfavourite = new YTeacherownquestion;
            $questiontext = YQuestiontext::model()->findByPk($_idquestiontext);
            $teacherfavourite->idmaterialdo = $questiontext->idmaterialdo;
            $teacherfavourite->idownerdo = $questiontext->idownerdo;
            $teacherfavourite->idparagraphdo = $questiontext->idparagraphdo;
            $teacherfavourite->idsectiondo = $questiontext->idsectiondo;
            $teacherfavourite->idquestiondo = $questiontext->idquestiondo;
            $teacherfavourite->idsubjectdo = $questiontext->idsubjectdo;
            $teacherfavourite->toq_questiontext_id = $questiontext->id;
            $teacherfavourite->idteacherdo = $_idteacherdo;
            $teacherfavourite->save();
            
            $questiontext->countfavorite = intval($questiontext->countfavorite) + 1;
            $questiontext->save();
	}

	/**
	 * borrow specified question (considering complexity) for specified group
	 * @access public
	 * @param JLSubgroup idsubgroup
	 * @param JLQuestionmeta idquestionmeta
	 * @ParamType subgroup JLSubgroup
	 * @ParamType idquestionmeta JLQuestionmeta
	 */
	public function borrowQuestionGroup($_idsubgroup, $_idquestionmeta) {
            $questionmetamodel = YQuestionmeta::model();
            $transactionQM = $questionmetamodel->getDbConnection()->beginTransaction();
            try{
                (object)$questionmeta = $questionmetamodel->findByPk($_idquestionmeta);
                $countactualborrows = $questionmeta->countactualborrows;
                $questionmeta->countactualborrows = intval($countactualborrows) + 1;
                
                if($questionmeta->save()){
                    $transactionQM->commit();
                } else {
                    $transactionQM->rollback();
                }
            } catch (Exception $e){
                $transactionQM->rollback();
                throw $e;
            }
            $questionforgroupmodel = YQuestionforgroup::model();
            $transactionQFG = $questionforgroupmodel->getDbConnection()->beginTransaction();
            try{
                (object)$questionmeta = $questionmetamodel->findByPk($_idquestionmeta);
                $questionforgroup = new YQuestionforgroup;
                $questionforgroup->idownerdo = $questionmeta->idownerdo;
                $questionforgroup->idmaterialdo = $questionmeta->idmaterialdo;
                $questionforgroup->idsectiondo = $questionmeta->idsectiondo;
                $questionforgroup->idsubjectdo = $questionmeta->idsubjectdo;
                $questionforgroup->idparagraphdo = $questionmeta->idparagraphdo;
                $questionforgroup->idquestiondo = $questionmeta->idquestiondo;
                $questionforgroup->qfg_questionmeta_id = $questionmeta->id;
                $questionforgroup->qfg_subgroup_id = $_idsubgroup;
                $questionforgroup->isborrowed = 1;
                if($questionforgroup->save()){
                    $transactionQFG->commit();
                } else {
                    $transactionQFG->rollback();
                }
            } catch (Exception $e){
                $transactionQFG->rollback();
                throw $e;
            }
	}

	/**
	 * remove specified question from system Y
	 * @access public
	 */
	public function removeQuestionMaterial( $_idquestiondo, $_idmaterialdo) {
            $criteria = new CDbCriteria();
            $criteria->condition = "idquestiondo=:idq";
            $criteria->params = array(":idq" => $_idquestiondo);
            $questiontextObj = YQuestiontext::model()->find($criteria);
            if($questiontextObj){
                $questiontextObj->isusedbyowner = 0;
                $questiontextObj->save();
                
                $command = new CDbCommand(Yii::app()->dbY);
                $command->select("id");
                $command->from("y_questionforgroup");
                $command->where("idmaterialdo=:idm AND idquestiondo=:idq AND isborrowed = 0 AND datemoved IS NULL", array(":idm" => $_idmaterialdo, ":idq" => $_idquestiondo));
                $idquestionforgroupDel = $command->queryColumn();
                QuestionManager::deleteQuestionsGroup($idquestionforgroupDel);
                $command->reset();
                $command->select("id");
                $command->from("y_questionforgroup");
                $command->where("idmaterialdo=:idm AND idquestiondo=:idq AND isborrowed = 0 AND datemoved IS NOT NULL", array(":idm" => $_idmaterialdo, ":idq" => $_idquestiondo));
                $idquestionforgroupUpd = $command->queryColumn();
                YQuestionforgroup::model()->updateByPk($idquestionforgroupUpd, array("deleted" => 1));
            }
	}

	/**
	 * remove specified question from specified group
	 * @access public
	 */
	public function removeQuestionGroup($_idsubgroup, $_idquestionmeta) {
            $questionmeta = YQuestionmeta::model()->findByPk($_idquestionmeta);
            $criteria = new CDbCriteria();
            $criteria->condition = "qfg_questionmeta_id=:idqm AND qfg_subgroup_id=:ids AND deleted = 0";
            $criteria->params = array(":idqm" => $_idquestionmeta, ":ids" => $_idsubgroup);
            $questionforgroup = YQuestionforgroup::model()->find($criteria);
            if($questionforgroup->isborrowed){
                $questionmeta->countactualborrows = intval($questionmeta->countactualborrows) - 1;
                $questionmeta->save();
            }
            if($questionforgroup->datemoved){
                $questionmeta->deleted = 1;
                $questionmeta->save();
            } else {
                YQuestionforgroup::model()->deleteByPk($questionforgroup->id);
            }
	}

	/**
	 * remove specified question from teacher's favorite questions
	 * @access public
         * @param int idquestiontext
         * @param int idteacher
	 */
	public function removeQuestionFavorite($_idquestiontext, $_idteacherdo) {
            $questiontext = YQuestiontext::model()->findByPk($_idquestiontext);
            $criteria = new CDbCriteria();
            $criteria->condition = "qm_questiontext_id=:idqt AND idteacherdo=:idt";
            $criteria->params = array(":idqt" => $_idquestiontext, ":idt" => $_idteacherdo);
            $teacherownquestion = YTeacherownquestion::model()->find($criteria);
            $teacherownquestion->delete();
            
            $questiontext->countfavorite = intval($questiontext->countfavorite) - 1;
            $questiontext->save();
	}

	/**
	 * get array of questions specified absolutecomplexity and specified material
	 * @access public
	 * @param int idmaterial
	 * @param int absolutecomplexity
	 * @return JLQuestionmeta[]
	 * @ParamType idmaterial int
	 * @ParamType absolutecomplexity int
	 * @ReturnType JLQuestionmeta[]
	 */
	public function getQuestionsMaterialByAbsCompl($idmaterial, $absolutecomplexity) {
		throw new Exception("Not yet implemented");
	}

	/**
	 * @access public
	 * @param int[] idquestiongroup
	 * @return void
	 * @ParamType idquestiongroup int[]
	 * @ReturnType void
	 */
	public function deleteQuestionsGroup(array $idquestiongroup) {
            $questionforgroups = YQuestionforgroup::model()->findAllByPk($idquestiongroup);
            $questionmeta = array();
            foreach($questionforgroups as $questionforgroup){
                if(!in_array($questionforgroup->qfg_questionmeta_id, $questionmeta)){
                    $questionmeta[] = $questionforgroup->qfg_questionmeta_id;
                }
            }
            $numQuestionForGroupDeleted = YQuestionforgroup::model()->deleteByPk($idquestiongroup);
//            echo "Rows deleted (questionforgroup) - ".$numQuestionForGroupDeleted.PHP_EOL;
            $toDelete = array();
            foreach ($questionmeta as $idquestionmeta) {
                $questionmetaObj = YQuestionmeta::model()->findByPk($idquestionmeta);
                $idquestiontext = $questionmetaObj->qm_questiontext_id;
                $command = new CDbCommand(Yii::app()->dbY);
                $command->select("SUM(countactualborrows) AS countAllBorrows");
                $command->from("y_questionmeta");
                $command->where("qm_questiontext_id=:idqt", array(":idqt"=>$idquestiontext));
                $borrowsAllCount = $command->queryScalar();
                if($borrowsAllCount == 0){
                    if(!in_array($idquestiontext, $toDelete)){
                        $toDelete[] = $idquestiontext;
                    }
                }
            }
//            echo "Questiontext to delete - ".implode(",", $toDelete).PHP_EOL;
            $numQuestionTextDeleted = YQuestiontext::model()->deleteByPk($toDelete);
//            echo "Rows deleted - ".$numQuestionTextDeleted.PHP_EOL;
	}

	/**
	 * @access public
	 * @param int[] parameter
	 * @return int[]
	 * @ParamType parameter int[]
	 * @ReturnType int[]
	 */
	public function deleteLearnerQuestionsAndHistoryByQuestionMeta(array $idquestionmeta, $idlearner) {
            $command = new CDbCommand(Yii::app()->dbY);
            $arraySpisokLearnerquestionID = $command->select("id")
                    ->where(array("and", "splq_learner_id=:idl", array("in","splq_questionmeta_id", $idquestionmeta)), array(":idl"=>$idlearner))
                    ->from("y_splearnerquestion")
                    ->queryColumn();
            $log35 = "|\t|SPLearnerquestion ID Array - [".
                        implode(", ", $arraySpisokLearnerquestionID)."]";
            $command->reset();
            $arraySpisokLearnerquestionhistoryID = $command->select("id")
                    ->where(array("and", "splqh_learner_id=:idl", array("in","splqh_questionmeta_id", $idquestionmeta)), array(":idl"=>$idlearner))
                    ->from("y_splearnerquestionhistory")
                    ->queryColumn();
            $log36 = "|\t|SPLearnerquestionhistory ID Array - [".
                        implode(", ", $arraySpisokLearnerquestionhistoryID)."]";
            $numberLearnerQuestionsRowsDeleted = YSPLearnerquestion::model()->deleteByPk($arraySpisokLearnerquestionID);
            $numberLearnerQuestionsHistoryRowsDeleted = YSPLearnerquestionhistory::model()->deleteByPk($arraySpisokLearnerquestionhistoryID);
            
//            echo $log35.PHP_EOL;
//            echo "|\t|Rows deleted - ".$numberLearnerQuestionsRowsDeleted.PHP_EOL;
//            echo $log36.PHP_EOL;
//            echo "|\t|Rows history deleted - ".$numberLearnerQuestionsHistoryRowsDeleted.PHP_EOL;
	}
        /**
         * Метод переносит вопрос/вопросы учеников из активного состояния в историю
         * @param mixed $_splearnerquestionIDorArrayID single ID of record in spisoklearnerquestion or Array of ID
         * @return mixed array, where key is idspisoklearnerquestion, values are: "splq_questionmeta_id", "isMovedToHistory", "isDeleted"
         */
        public function moveLearnerQuestionsToHistory($_splearnerquestionIDorArrayID){
            $arrayIDsplearnerquestion = array();
            $resultArray = array();
            if(!is_array($_splearnerquestionIDorArrayID)){
                $arrayIDsplearnerquestion[] = $_splearnerquestionIDorArrayID;
            } else {
                $arrayIDsplearnerquestion = $_splearnerquestionIDorArrayID;
            }
            foreach ($arrayIDsplearnerquestion as $IDsplearnerquestion){
                $ObjSplearnerquestion = YSPLearnerquestion::model()->findByPk($IDsplearnerquestion);
                # Проверить, есть ли такой вопрос в истории
                $arrayObjToDelete = YSPLearnerquestionhistory::model()->findAll("splqh_learner_id=:l_id AND splqh_questionmeta_id=:qm_id", 
                        array(":l_id"=>$ObjSplearnerquestion->splq_learner_id, ":qm_id"=>$ObjSplearnerquestion->splq_questionmeta_id));
                foreach($arrayObjToDelete as $objToDelete){
                    $objToDelete->delete();
                }
                # Перенести вопрос в историю
                $toIns = new YSPLearnerquestionhistory();
                $toIns->idownerdo = $ObjSplearnerquestion->idownerdo;
                $toIns->idmaterialdo = $ObjSplearnerquestion->idmaterialdo;
                $toIns->idsectiondo = $ObjSplearnerquestion->idsectiondo;
                $toIns->idparagraphdo = $ObjSplearnerquestion->idparagraphdo;
                $toIns->idquestiondo = $ObjSplearnerquestion->idquestiondo;
                $toIns->idsubjectdo = $ObjSplearnerquestion->idsubjectdo;
                $toIns->mark = $ObjSplearnerquestion->mark;
                $toIns->countrightanswer = $ObjSplearnerquestion->countrightanswer;
                $toIns->countallanswer = $ObjSplearnerquestion->countallanswer;
                $toIns->splqh_learner_id = $ObjSplearnerquestion->splq_learner_id;
                $toIns->splqh_questionmeta_id = $ObjSplearnerquestion->splq_questionmeta_id;
                $isSaved = $toIns->save();
                $deletedRows = YSPLearnerquestion::model()->deleteByPk($IDsplearnerquestion);
                $resultArray[$IDsplearnerquestion]["splq_questionmeta_id"] = $ObjSplearnerquestion->splq_questionmeta_id;
                $resultArray[$IDsplearnerquestion]["isMovedToHistory"] = $isSaved;
                if($deletedRows == 1){
                    $resultArray[$IDsplearnerquestion]["isDeleted"] = true;
                } else {
                    $resultArray[$IDsplearnerquestion]["isDeleted"] = false;
                }
            }
            return $resultArray;
        }
}
?>