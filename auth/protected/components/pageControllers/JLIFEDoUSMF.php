<?php
Yii::import('application.components.pageControllers.core.USMFPageController');
/**
 * Controller:
 * 
 */
class JLIFEDoUSMF extends USMFPageController
{
	public $layout='//layouts/DoUSMF';

	/* do fields */
	protected $schools = array();
	protected $schoolsOld = array();
	protected $schoolsOrder = array();
	protected $learnersOrder = array();

	protected $selectSchool = null;			// idSchool from DB
	protected $selectRole = null;			// route type LETTER (class ROLESTYPES)
	protected $selectRoleRouteType = null;	//
	protected $selectAdress = null;			// idAdress from DB
	protected $selectLearner = null;		// idLearner from DB
	protected $selectLearnerOrder = null;	// порядкоывй номер ученика по отношении к родителю


	private $changeItemFAQ = false;

	/*
	 * Overloading : Method initialization requariament for subl menu items on page
	 */
	protected function initPagesOrderSublMenu()
	{
		$cabinet_url = '/../pages/do/cabinet.php';
		return array(
			ROLESTYPES::$Learner => array(
				array('namePage'=>'Расписание', 'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(1), 's'=>'1') ),
				array('namePage'=>'Предметы', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(1), 's'=>'2') ),
				array('namePage'=>'Журнал', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(1), 's'=>'3') ),
				array('namePage'=>'Результаты тестов',
												'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(1), 's'=>'4') ),
				array('namePage'=>'Evolution', 	'url'=>array('do/evolution','school'=>null, 'role'=>ROLESTYPES::RouteType(1), )), ),
			ROLESTYPES::$Responsible => array(
				array('namePage'=>'Расписание', 'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(8), 'learnerOrder'=>null, 's'=>'1') ),
				array('namePage'=>'Предметы', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(8), 'learnerOrder'=>null, 's'=>'2') ),
				array('namePage'=>'Журнал', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(8), 'learnerOrder'=>null, 's'=>'3') ),
				array('namePage'=>'Результаты тестов',
												'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(8), 'learnerOrder'=>null, 's'=>'4') ),
				array('namePage'=>'Evolution', 	'url'=>array('do/evolution','school'=>null, 'role'=>ROLESTYPES::RouteType(8), 'learnerOrder'=>null, )), ),
			ROLESTYPES::$Teacher => array(
				array('namePage'=>'Расписание', 'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(2), 's'=>'1') ),
				array('namePage'=>'Предметы', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(2), 's'=>'2') ),
				array('namePage'=>'Журнал', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(2), 's'=>'3') ),
				array('namePage'=>'Результаты тестов',
												'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(2), 's'=>'4') ),
				array('namePage'=>'Evol NEW', 	'url'=>array('do/evolution','school'=>null, 'role'=>ROLESTYPES::RouteType(2), ) ),
			),
			ROLESTYPES::$Ko => array(
				array('namePage'=>'Классы', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(4), 's'=>'1') ),
				// array('namePage'=>'Расписание', 'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(4), 's'=>'2') ),
				array('namePage'=>'Расписание', 'url'=>array('do/schedule', 'school'=>null, 'role'=>ROLESTYPES::RouteType(4), ) ),
				array('namePage'=>'Журнал', 	'url'=>array($cabinet_url, 'school'=>null, 'type'=>ROLESTYPES::RouteType(4), 's'=>'3') ),
				array('namePage'=>'Evolution', 	'url'=>array('do/evolution','school'=>null, 'role'=>ROLESTYPES::RouteType(4), )), ),
		);
	}

	/*
	 * Overloading : Инициализация контролера
	 */
	public function init()
	{
        parent::init();
		//	1.	Выгрузить из базы РЕАЛЬНЫЕ данные
			$ProfileManager = new ProfileManager();
			$tmp = $ProfileManager->getSchoolsRolesUpdate(null, array(ROLESTYPES::$Learner, ROLESTYPES::$Teacher, ROLESTYPES::$Ko, ROLESTYPES::$Responsible));
			$this->schoolsOrder = $tmp['schoolsOrder'];
			$this->learnersOrder = $tmp['learnersOrder'];
			$this->schools = $tmp['schoolsNotKeys'];
			$this->schoolsOld = $tmp['schoolsKeys'];

		//	2.	Получить то что есть из URL.
			$this->selectSchool = $_GET['school'] ? $_GET['school'] : null;
			$this->selectRoleRouteType = $_GET['role'] ? $_GET['role'] : null;
			$this->selectLearnerOrder = $_GET['learnerOrder'] ? $_GET['learnerOrder'] : null;
			// $this->selectAdress = $_GET['adress'] ? $_GET['adress'] : null;

		//	3.	Проинициализировать private поля: Определть что данные валидны и принадлежат profile. Определить чего не хвататет.
			$orderNumber_school = array_search($this->selectSchool, $this->schoolsOrder, true);
				if($this->selectSchool === null) { throw new Exception(" MAY BE 404.html :SCHOOL(".$this->selectSchool.") NOT SET IN REQUET", 1); }
				else {
					if($orderNumber_school === FALSE) throw new Exception("MAY BE 404.html : PROFILE ".Yii::app()->user->id." HAS NOT SCHOOL(".$this->selectSchool.")", 1);
				}
			# selectSchool
				$_rolesSSchool = (array)$this->schools[$orderNumber_school];
				// role=null => проинициализировать
				if($this->selectRoleRouteType === null) {
					$this->selectRole = key($_rolesSSchool['roles']); // взять первую роль из массива ролей школы.
					$this->selectRoleRouteType = ROLESTYPES::RouteType($this->selectRole);
				}
				else {
					// role!=null => проверить => если false new 404
					$this->selectRole = ROLESTYPES::getRoleByRouteType($this->selectRoleRouteType);

					if(!array_key_exists($this->selectRole, $_rolesSSchool['roles']))
						throw new Exception("MAY BE 404.html : PROFILE ".Yii::app()->user->id." HAS NOT ROLE(".$this->selectRole.") IN SCHOOL(".$this->selectSchool.")", 1);
				}
				$_roleSSchool = $_rolesSSchool['roles'][$this->selectRole];

			# selectSchool selectRole selectRoleRouteType
				// adress=null => проинициализировать
				// adress!=null => проверить => если false new 404
				if($this->selectAdress === null) {$this->selectAdress = $_roleSSchool['idadress']; }
				else if($this->selectAdress !== $_roleSSchool['idadress'])
						throw new Exception(
						"MAY BE 404.html : PROFILE ".Yii::app()->user->id." HAS NOT ADRESS(".$this->selectAdress.") ROLE(".$this->selectRole.") IN SCHOOL(".$this->selectSchool.")", 1);

			# selectSchool selectRole selectRoleRouteType selectAdress
				if($this->selectRole === ROLESTYPES::$Responsible)
				{
					$_learnersRespSSchool = $_roleSSchool['learners'];
					// role='l' && learner!=null => проверить => если false new 404
					if($this->selectLearnerOrder === NULL) $this->selectLearnerOrder = 1;
					if($this->selectLearnerOrder <= 0 || $this->selectLearnerOrder > count($_learnersRespSSchool))
						throw new Exception(
						"MAY BE 404.html : PROFILE ".Yii::app()->user->id." HAS NOT LEARNER(".$this->selectLearnerOrder.") FOR RESPONSIBLE IN SCHOOL(".$this->selectSchool.")", 1);

					$_learnerOneResponsibleSSchool = $_learnersRespSSchool[($this->selectLearnerOrder-1)];
					$this->selectLearner = $_learnerOneResponsibleSSchool['idlearner'];
				}

			# selectSchool selectRole selectRoleRouteType selectAdress selectLearner selectLearnerOrder
		//	3.1.	Изменить дефолтный action
			if($this->selectRole)
				$this->defaultAction = ROLESTYPES::getEnLabaelByRouteType($this->selectRoleRouteType);
	}

	/*
	 * Overloading : Method initialization subl menu items as CMenu template
	 */
	protected function initItemsSublMenu()
	{
		$items_sublMenu = array();
		if(!Yii::app()->user->isGuest) {
			$roles = $this->schools;
		}
		foreach ($roles as $orderSchool => $school)
		{
			$idschool = $school['idschool'];
			$schoolName = $school['schoolname'];

		    $school_roles_items = array();
		    foreach ($school['roles'] as $roletype => $role_context)
		    {
				// $schoolName = $role_context['schoolname'];
		    	if(isset($role_context['learners']) && count($role_context['learners'])){
					foreach ($role_context['learners'] as $orderLearner => $learner) {
						$subitems = $this->_prepareDataSublMenu($this->changeItemFAQ, $idschool, $roletype, $role_context, $learner['idlearner']);
						$items_sublMenu[] = $this->itemRender('S('.$idschool.') '.$learner['firstname'],
							$subitems[0]['url'], null, $subitems
						);
					}
		    	}
				else
				{
					$subitems = $this->_prepareDataSublMenu($this->changeItemFAQ, $idschool, $roletype, $role_context);
					$items_sublMenu[] = $this->itemRender('S('.$idschool.') '.$schoolName.' '.ROLESTYPES::LabelType($roletype, 1),
						$subitems[0]['url'], null, $subitems
					);
				}
		    }
		}
		return $items_sublMenu;
	}

	/*
	 * ????????
	 */
	private function _prepareDataSublMenu(&$changeItemFAQ, $idschool, $roletype, $role_context, $idlearner=null)
	{
		$subitems = array();
		foreach ($this->pagesOrder[$roletype] as $key => $page) {

			$url_cur = $page['url'];
			$roletype = $page['url']['type'] ? $page['url']['type'] : $page['url']['role'];
			// $page['numberNP'] = $page['url'][0].'/'.ROLESTYPES::getEnLabaelByRouteType($roletype);
			// $page['numberNP_NEW'] = NumbersPages::NumberPageByRoute( $page['url'][0].'/'.ROLESTYPES::getEnLabaelByRouteType($roletype) );


			if(array_key_exists('school', $url_cur)) $url_cur['school'] = $idschool;
		// get order number learner for school/role[responsible]
			if($idlearner!==null && array_key_exists('learnerOrder', $url_cur))
				$url_cur['learnerOrder'] = $this->_getOrderNumberLearner($idschool, $idlearner);
			$subitems[] = $this->itemRender($page['namePage'], $url_cur);
		}
		if(!$changeItemFAQ) $subitems = $this->replaceItem($subitems, $changeItemFAQ, $idlearner);

		return $subitems;
	}

	/*
	 * Overloading : Метод выполняемый после инициализации элементов меню
	 */
	protected function afterInitItemsSublMenu()
	{
	}

	/*
	 * Заменить элемент на "Справка"
	 */
	private function replaceItem($items, &$changeItemFAQ, $idlearner=null)
	{

	    $controller = $this->id;
	    // нужно будет заменить вкладку на Page(FAQ)
	    if($controller === 'faq') {

	        foreach ($items as $key => $item) {
	            if(
					$item['url']['school']==$_GET['school'] && ( $item['url']['type']==$_GET['role'] || $item['url']['role']==$_GET['role'] ) ||
					$item['url']['school']==$_GET['school'] && ( $item['url']['type']==$_GET['role'] || $item['url']['role']==$_GET['role'] )
						&& $item['url']['learnerOrder']==$_GET['learnerOrder'] )
	            {
					// min-width: 10px;
					// padding: 4px 7px;
					// border-radius: 14px;
					// margin: 0px 0px 0 4px;
					// background-image: url(/img/shut_down.png);
					// background-size: 20px;
					// background-repeat: no-repeat;
					// background-position: 2px 2px;
					$replacedItem = $items[$key];
					$replacedItem_url = $replacedItem['url'];
					if(stripos($replacedItem_url[0], $_GET['_fc']))
					{
						$url_cur = array();
						if(array_key_exists('school', $replacedItem_url)) $url_cur['school'] = $replacedItem_url['school'];
						if(array_key_exists('role', $replacedItem_url)) $url_cur['role'] = $replacedItem_url['role'];
						if(array_key_exists('type', $replacedItem_url)) $url_cur['type'] = $replacedItem_url['type'];
						if(array_key_exists('s', $replacedItem_url)) $url_cur['s'] = $replacedItem_url['s'];
						if(array_key_exists('learnerOrder', $replacedItem_url)) $url_cur['learnerOrder'] = $replacedItem_url['learnerOrder'];

						// $actionRole = ROLESTYPES::getActionByRole($url_cur['role']);
						$actionRole = ROLESTYPES::getEnLabaelByRouteType($this->selectRoleRouteType);
						// var_dump($actionRole);
						$faq = $this->itemRender(
							$item['label'],
							CMap::mergeArray(array('/faq/'.$actionRole,), $url_cur),
							array(
								$this->itemRender(
									'К странице',
									CMap::mergeArray(array( '/'.str_replace('/faq', '', Yii::app()->getRequest()->getPathInfo()) ), array())
									),
								$this->itemRender(
									'Справка',
									CMap::mergeArray(array('/faq/'.$actionRole,), $url_cur)
									),
								)
						);
						$items[$key] = $faq;
					}
	            }
	        }
	    }
	    // return $changeItemFAQ ? $items : array();
	    return $items;
	}

	/*
	 * Генерация элемента меню
	 */
	private function itemRender($label, $url, $items=array(), $sublitems=array())
	{
	    if(is_array($items) && count($items))
	        return array('label'=>$label, 'url'=>$url, 'items' => $items);
	    else if(is_array($sublitems) && count($sublitems))
	        return array('label'=>$label, 'url'=>$url, 'sublitems' => $sublitems);
	    else
	        return array('label'=>$label, 'url'=>$url);
	}
	protected function _getOrderNumberLearner($idschool, $idlearner)
	{
		if($idschool===null || $idlearner===null)
			throw new Exception("SCHOOL(".$idschool.") AND LEARNER(".$idlearner.")", 1);
		$learners = $this->learnersOrder[$idschool][ROLESTYPES::$Responsible];
		$orderNumber_learner = (array_search($idlearner, $learners, true)+1);
		return ($orderNumber_learner!==FALSE) ? (string)$orderNumber_learner : null;
	}


	// public function missingAction($actionID)
	// {
	//   Yii::trace('вызван не существующие действие '.$actionID);
	//   $this->redirect('roma/index');
	//   // или без редиректа
	//   $this->run('index');
	// }
}