<?php

class ProfileController extends JLIFESocialUBMF
{
	public $defaultAction = 'general';
	protected $pageName = 'Профайл';

	public function actionGeneral()
	{
		$baseUrl = Yii::app()->baseUrl;
	    /* @var $cs CClientScript */
	    $cs = Yii::app()->clientScript;
	    $cs->registerCssFile($baseUrl.'/css/social/style_general.css');

	    // $criteria = new CDbCriteria;
	    // $criteria->select='firstname';
	    // Yii::app()->user->id);
	    // $post = Profile::model()->findByPk(
	    // 	Yii::app()->user->id,
	    // 	array('select'=>array('birthday','country'))
	    // );


//	     $dataProfile = Profile::model()->find(
//	     	array(
//		 		'select'=>array('birthday','country', 'city', 'telephone', 'mobile'),
//		 		'condition'=>'id=:id',
//		 		'params' => array(':id'=>Yii::app()->user->id, ),
//		 	)
//	     );
//
//	     // print_r($dataProfile);
//		 $this->render('general', array('birthday'=>$dataProfile->birthday, 'country'=>$dataProfile->country, 'city'=>$dataProfile->city, 'telephone'=>$dataProfile->telephone, 'mobile'=>$dataProfile->mobile));


		$test = 'Hello';
//		LevelManager::recalculateAvailableQuestions(array(1,2));
//        LevelManager::calculateCountQuestions();
        $idQuestYAnswDo = array();
//        LevelManager::finishSubevelY(1, $idQuestYAnswDo);
//        LevelManager::finishLevelY();

        $this->render('general', array('test'=>$subgroupRatio));
	}

	// public function actionTest()
	// {
	// 	$baseUrl = Yii::app()->baseUrl;
	//     /* @var $cs CClientScript */
	//     $cs = Yii::app()->clientScript;
	//     $cs->registerCssFile($baseUrl.'/css/social/style_general.css');

	//     $test = 'Hello';
	//     $this->render('general', array('test'=>$test));

	// }

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