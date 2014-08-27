<?php
/**
 * @access public
 * @author CHERRY
 */
class SubgroupManager {

	/**
	 * Устанавливает выбранный материал указанной группе
	 * @access public
	 * @param int idmaterial
	 * @param int idsubgroup
	 * @ParamType idmaterial int
	 * @ParamType idsubgroup int
	 */
	public function setMaterialGroup($idmaterial, $idsubgroup) {
            throw new Exception("Not yet implemented");
	}

	/**
	 * add learner to group by idlearner and idgroup
	 * @access public
	 * @param int idlearner
	 * @param int idsubgroup
	 * @ParamType idlearner int
	 * @ParamType idsubgroup int
	 */
	public function addLearnerToGroup($idlearner, $idsubgroup) {
		throw new Exception("Not yet implemented");
	}

	/**
	 * delete learner from group by idlearner and idgroup
	 * @access public
	 */
	public function deleteLearnerFromGroup() {
		throw new Exception("Not yet implemented");
	}

	/**
	 * transfer learner from one group to other group by idlearner, idnewgroup, idoldgroup
	 * @access public
	 */
	public function transferLearnerBetweenGroups() {
		throw new Exception("Not yet implemented");
	}

	/**
	 * get ID subgroup array which study specified material
	 * @access public
	 * @param int idmaterial
	 * @param int absolutecomplexity
	 * @return int[]
	 * @ParamType idmaterial int
	 * @ParamType absolutecomplexity int
	 * @ReturnType int[]
	 */
	public function getSubgroupsStudyMaterial($idmaterial, $absolutecomplexity) {
		throw new Exception("Not yet implemented");
	}

	/**
	 * @access public
	 * @param int idsubgroupdo
	 * @param int absolutecomplexity
	 * @param int idschooldo
	 * @ParamType idsubgroupdo int
	 * @ParamType absolutecomplexity int
	 * @ParamType idschooldo int
	 */
	public function createGroup($idsubgroupdo, $absolutecomplexity, $idschooldo) {
		throw new Exception("Not yet implemented");
	}

	/**
	 * @access public
	 */
	public function setSectionSubgroupAvaliable() {
		throw new Exception("Not yet implemented");
	}
}
?>