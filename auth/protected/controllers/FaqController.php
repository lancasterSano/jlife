<?php

class FaqController extends JLIFEDoUSMF
{
	public $layout='//layouts/DoUSMFModern';
	public $findPageNumber = null;
	public $findPageRoute = null;
	protected $pageName = 'Справка';

	public function beforeAction($action) {
		// $this->findPage = $_GET['_c'];//$_GET['fPage'];

		$this->findPageRoute = 'do/'.$_GET['_fc'].'/'.ROLESTYPES::getEnLabaelByRouteType($this->selectRoleRouteType);

		$this->findPageNumber = NumbersPages::NumberPageByRoute($this->findPageRoute);

		if($this->findPageNumber) $this->pageName = 'Справка:'.$this->findPageNumber;

	    if( parent::beforeAction($action) ) {
	    	$baseUrl = Yii::app()->baseUrl;
	        /* @var $cs CClientScript */
	        $cs = Yii::app()->clientScript;
	        $cs->registerCssFile($baseUrl.'/css/style_help.css');
	        $cs->registerCssFile($baseUrl.'/css/custom-theme/jquery-ui-1.9.2.custom.css');

	        // $cs->registerScriptFile('../lib/lib_jquery/ui/js/jquery-ui-1.9.2.custom.min.js' );
	        $cs->registerScriptFile($baseUrl.'/js/jquery-ui-1.9.2.custom.js' );
	        $cs->registerScriptFile($baseUrl.'/js/faq.js' );
			
	        return true;
	    }
	    return false;
	}

	// public function actionSearch()
	// {
	// 	$this->render('index');
	// }

	public function actionSearch() 
	{
		$model= Question::model()->with(array(
			'answers',
			'answers.splabelanswers',
			'answers.splabelanswers.idlabel0',
			)
		);
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Question']))
			$model->attributes=$_GET['Question'];

		$this->render('search',array(
			'model'=>$model,
		));
	}
	public function actionLearner()
	{
		$this->render('index');
	}
	public function actionTeacher()
	{
		$this->render('index');
	}
	public function actionKo()
	{
//                QuestionManager::borrowQuestionGroup(4, 50);
//                QuestionManager::borrowQuestionGroup(5, 50);
//                QuestionManager::removeQuestionGroup(5, 19);
//                QuestionManager::addQuestionFavorite(26, 16);
//                QuestionManager::removeQuestionFavorite(26, 16);
//                QuestionManager::deleteQuestionsMaterial(array(1, 2, 3, 9, 10, 11, 12));
//            QuestionManager::removeQuestionMaterial(21, 21);
//		$this->render('index');
	}
	public function actionResponsible()
	{
		$this->render('index');
	}
	public function actionIndex()
	{
		$school = $_GET['school'];
		$role = $_GET['role'];
		$idadress = $_GET['idadress'];
		$this->render('index');
	}
	public function actionIndexq()
	{
		$arrayofmass = array("один", "два", "три");
		$this->render('index', array('arrayofmass'=>$arrayofmass));
	}
	public function actionIndexw()
	{
		$this->render('index');
	}
	public function actionIndexs()
	{
		$this->render('index');
	}
	public function actionIndexb()
	{
		$this->render('index');
	}
	public function actionIndexd()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}