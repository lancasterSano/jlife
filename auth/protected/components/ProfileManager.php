<?php

Yii::import('application.components.common.settings');

/**
* Класс Profile
*/
class ProfileManager
{
	private $_idProfile;
	private $isdefaultava = 1;
	private $sex = 1;
	private $isdefaulthb = 1;

	function __construct()
	{
		$this->_idProfile = Yii::app()->user->id;
	}
	/*
	 * return Roles of profile with filter ($filter_schools, $filter_roles)
	 *			Roles 		WHERE profile_id = $auth_id_profile AND datefinish IS NULL
	 * 						OREDR BY idschool, datestart, id ASC
	 *			Learners 	WHERE deleted = 0
	 * 						OREDR BY schoolS_id, responsibleS_id1, relationS_id ASC
	 * retrun type : array(
	 *		schoolsOrder 	= array(orderNumber=>idSchool,)
	 *		learnersOrder 	= array(idSchool => array( roletype => array( orderNumber => idLearner), ), )
	 *		schoolsNotKeys 	= array( orderNumber => Array
							                (
							                    [idschool] => 1
							                    [schoolname] => Средняя школа №51
							                    [roles] => Array
							                        (
							                            roletype => Array
							                                (
							                                    [roletype] => roletype
							                                    [roleTitle] => Responsible
							                                    [idadress] => 4
							                                    [learners] => Array
							                                        (
							                                            orderNumber => Array
							                                                (
							                                                    [idlearner] => 19
							                                                    [firstname] => Сафиулла
							                                                    [lastname] => Бахраман
							                                                    [middlename] => 
							                                                    [fio] => Сафиулла Бахраман
							                                                )
	                                                                )
	                                                        )
	                                                )
							                )
	 *		schoolsKeys 	= array( idSchool => Array
						                (
						                    roletype => Array
						                        (
						                            [roleTitle] => Responsible
						                            [idadress] => 4
						                            [schoolname] => Средняя школа №51
						                            [learners] => Array
						                                (
						                                    orderNumber => Array
						                                        (
						                                            [idlearner] => 19
						                                            [firstname] => Сафиулла
						                                            [lastname] => Бахраман
						                                            [middlename] => 
						                                            [fio] => Сафиулла Бахраман 
						                                        )
						                                )
						                        )
						                )
						        )
	 *	)
	 */
	public function getSchoolsRolesUpdate($filter_schools=array(),$filter_roles=array())
	{
		$schoolsKeys = array();
		$schoolsNotKeys = array();
		$schoolsOrder = array();
		$learnersOrder = array();

		$criteria = new CDbCriteria;
			$criteria->condition = 'profile_id = :idProfile AND datefinish IS NULL';
			$criteria->params = array(':idProfile'=>$this->_idProfile);		
			$criteria->order = 'idschool, role, datestart, id ASC';
			if(is_array($filter_schools) && count($filter_schools)){
				$criteria->addInCondition('idschool',$filter_schools);
			}
			if(is_array($filter_roles) && count($filter_roles)){
				$criteria->addInCondition('role',$filter_roles);
			}
		$rez = RolesModel::model()->findAll($criteria);


		foreach ($rez as $id => $row) {
			# learnersOrder
				if($row['role'] == ROLESTYPES::$Responsible){
					$lr[] = (int)$row['idadress'];
				}
				$learnersOrder[$row['idschool']][$row['role']] = array();
			# schoolsOrder
				if(!array_search($row['idschool'], $schoolsOrder, true)) $schoolsOrder[] = $row['idschool'];
			# schoolsKeys
				// $schoolsKeys[$row['idschool']][$row['role']]['roleTitle'] = $this->getRoleTitle($row['role']);
				$schoolsKeys[$row['idschool']][$row['role']]['roleTitle'] = ROLESTYPES::LabelType($row['role'], false, true);
				$schoolsKeys[$row['idschool']][$row['role']]['idadress'] = (int)$row['idadress'];
				$schoolsKeys[$row['idschool']][$row['role']]['schoolname'] = $row['schoolname'];
				$schoolsKeys[$row['idschool']][$row['role']]['learners'] = array();
			# schoolsNotKeys
				$schoolOrder = array_search($row['idschool'], $schoolsOrder, true);
				$role = array(
					'roletype' => $row['role'],
					'roleTitle' => ROLESTYPES::LabelType($row['role'], false, true),
					'idadress' => (int)$row['idadress'],
					'learners' => array(),
				);
				$schoolsNotKeys[$schoolOrder]['idschool'] = $row['idschool'];
				$schoolsNotKeys[$schoolOrder]['schoolname'] = $row['schoolname'];
				$schoolsNotKeys[$schoolOrder]['roles'][$role['roletype']] = $role;
			
		}
		if(is_array($lr) && count($lr)){
			$cr_responivle = new CDbCriteria;
				$cr_responivle->condition = 'deleted IS FALSE';
				$cr_responivle->addInCondition('responsibleS_id1',$lr);
				$cr_responivle->with=array(
					'learnerSId1'=>array(
						'alias'=>'learner',
						'select'=>'firstname, lastname, middlename, iduser'),
				);
				$cr_responivle->order = 't.schoolS_id, t.responsibleS_id1, t.relationS_id ASC';
			$rez = DOSPResponsiblelearners::model()->findAll($cr_responivle);
			// print_r($rez); exit();
			foreach ($rez as $key => $relation) {
				if(isset($schoolsKeys[$relation['schoolS_id']][ROLESTYPES::$Responsible]))
				{
					$learner = array(
						'idlearner'=>$relation['learnerS_id1'], 
						'firstname'=>$relation->learnerSId1->firstname,
						'lastname'=>$relation->learnerSId1->lastname,
						'middlename'=>$relation->learnerSId1->middlename,
						'fio'=>$relation->learnerSId1->firstname.' '.$relation->learnerSId1->lastname.' '.$relation->learnerSId1->middlename,
					);
					# learnersOrder
						$learnersOrder[$relation['schoolS_id']][ROLESTYPES::$Responsible][] = $relation['learnerS_id1'];
					# schoolsKeys
						$schoolsKeys[$relation['schoolS_id']][ROLESTYPES::$Responsible]['learners'][] = $learner;
						
					# schoolsNotKeys
						$schoolOrder = array_search($relation['schoolS_id'], $schoolsOrder, true);
						$schoolsNotKeys[$schoolOrder]['roles'][ROLESTYPES::$Responsible]['learners'][] = $learner;
					// var_dump($relation->learnerSId1->firstname); ///!!!!!!!!!!!!!!!!!!!!!!!
				}
			}
			// echo Yii::trace(CVarDumper::dumpAsString($schoolsKeys),'____DB__');
		}
		return array( 'schoolsOrder' => $schoolsOrder, 'learnersOrder' => $learnersOrder, 'schoolsNotKeys' => $schoolsNotKeys, 'schoolsKeys' => $schoolsKeys, ); 
	}
	private function ______delete____getRoleTitle($role)
	{
		switch ($role) {
			case ROLESTYPES::$Learner:
				$role = 'Learner';
				break;
			case ROLESTYPES::$Teacher:
				$role = 'Teacher';
				break;
			case ROLESTYPES::$Ko:
				$role = 'Ko';
				break;
			case ROLESTYPES::$Responsible:
				$role = 'Responsible';
				break;
		}
		return $role;
	}

	/** * Путь к аватару из this(object) */
        public function ProfilePathAvatar($size = 0, $reload=false){
            return $this->getProjectPathAvatar($this->_idProfile, $this->isdefaultava, $size, $this->sex, $reload);
        }
        /** * Путь к подложке из this(object)*/
        public function ProfilePathHeadband($size = 4){
            return $this->getProjectPathHeadBand($this->_idProfile, $this->isdefaulthb, $size);
        }

    	static function getProjectPathAvatar($id, $default = true, $size = 0, $sex = null, $reload=false){
            if($default || ($id === NULL))
            {
                // default
                // $path = P_DEF_AVA_PHOTO;                
                if($id == null || $sex == null) { $id = -1; $sex = 1; }
                $path = 'd'.(($id % 9)+1).'.jpg';
                if($size == 1) return preg_replace("/SEX/", $sex, preg_replace("/IDPHOTO/", $path, Settings::P_DEF_AVAA));
                else if($size == 0) return preg_replace("/SEX/", $sex, preg_replace("/IDPHOTO/", $path, Settings::P_DEF_AVAS()));
            }
            else
            {
                $path = 'a'.$id.Settings::PR_TYPE_PHOTO;
                $reload_place = $reload?'?ord='.time():'';
                // not default
                if($size == 1) return preg_replace("/IDPHOTO/", $path, Settings::P_AVAA).$reload_place;
                else if($size == 0) return preg_replace("/IDPHOTO/", $path, Settings::P_AVAS).$reload_place;
            }
        }

        static function getProjectPathHeadBand($id, $default = true, $size = 0){
            if($default || ($id === NULL))
            {
                // default
                $path = Settings::P_DEF_HBB_PHOTO;
                return preg_replace("/IDPHOTO/", $path, Settings::P_DEF_HBB);
            }
            else 
            {
                $path = 'b'.$id.Settings::PR_TYPE_PHOTO;
                return preg_replace("/IDPHOTO/", $path, Settings::P_HBB);
            }
        }
}
?>