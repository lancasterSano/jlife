<?php

class EvolutionController extends JLIFEDoUSMF
{
	protected $pageName = 'Система роста';

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
		$this->render('index');
	}
	public function actionResponsible()
	{
		$this->render('index');
	}





	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionIndex2()
	{
		$this->render('index');
	}
	public function actionIndexThree()
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