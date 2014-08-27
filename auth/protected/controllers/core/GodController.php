<?php
Yii::import('application.components.pageControllers.core.JLIFEGodPanel');
Yii::import('application.models.do.forms.CreateSchool');

class GodController extends JLIFEGodPanel
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionCr_school()
	{
		// $model=new CreateSchool;
		// var_dump($_POST['ajax']);
		// // if it is ajax validation request
		// if(Yii::app()->request->isAjaxRequest)
		// {
		// 	$this->renderPartial('_quote', array('quote'=>$quote,));
		// 	// echo CActiveForm::validate($model);
		// 	// Yii::app()->end();
		// }
		// else {
		// 	if(isset($_POST['CreateSchool']))
		// 		$model->setAttributes($_POST['CreateSchool']);
		// 		if($model->validate()) {
		// 			$ns = new Schools;
		// 			$ns->name = $model->name;
		// 			$ns->save(false);

		// 			$model = new CreateSchool;
		// 		}
		// 	$this->render('cr_school',array('model'=>$model));			
		// }
		$model = new Schools;
		$this->render('cr_school',array('model'=>$model));			
	}
	public function actionCr_subject()
	{
		$this->render('index');
	}
	public function actionCr_section()
	{
		$this->render('index');
	}




	private $quotes = array(
		array('Просто','Рома'),
		array('Сложно','Олег'),
		array('Отлично','Влад'),
		);
	private function getRandowmQuotes()
	{
		return $this->quotes[array_rand($this->quotes, 1)];
	}
	function actionQuotes()
	{
		$this->render('quotes', array('quote'=>$this->getRandowmQuotes(),));
	}
	function actionGetQuote()
	{
		$quote = $this->getRandowmQuotes();
		if(Yii::app()->request->isAjaxRequest) {
			$this->renderPartial('_quote', array('quote'=>$quote,));
		} else {
			$this->render('quotes', array('quote'=>$quote,));
		}

	}
	// Uncomment the following methods and override them if needed
	// public function filters()
	// {
	// 	// return the filter configuration for this controller, e.g.:
	// 	// return array(
	// 	// 	'inlineFilterName',
	// 	// 	array(
	// 	// 		'class'=>'path.to.FilterClass',
	// 	// 		'propertyName'=>'propertyValue',
	// 	// 	),
	// 	// );
	// 	return array('ajaxOnly + getQuote',);
	// }
	/*

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