<?php
// Yii::import('application.components.USMFController');
class AuthController extends JLIFEO
{
	public $defaultAction = 'login';
	protected $pageName = 'Авторизация';

	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
	        $cs = Yii::app()->clientScript;
	        $cs->registerCssFile('/css/style_authorize_yii.css');
	        return true;
	    }
	    return false;
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters()
	{
		return array(
			// 'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
		);
	}
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('guest'),
				'users'=>array('?'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	public function actionIndex()
	{
		$url = Yii::app()->createUrl('/..'.'/pages/index.php'.'?r=2222');
        Yii::app()->user->setReturnUrl($url);
	}
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(Yii::app()->user->isGuest) {
			$model=new LoginForm;

			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login()) {

					// var_dump(Yii::app()->user->returnUrl);
					// exit();

					// костыль если нет правильного returnUrl => заменить его на главную страницу
					if(Yii::app()->user->returnUrl === "/auth/" 
                                                || Yii::app()->user->returnUrl === "/jlife/auth/" 
                                                || Yii::app()->user->returnUrl === "/auth/index.php")
					{
						// Yii::app()->user->setReturnUrl("../.."."/pages/index.php");
        				$url = Yii::app()->createUrl('/..'.'/pages/index.php'.'?r=18000');
						Yii::app()->user->setReturnUrl($url);
					}

					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		// display the login form
			$this->render('login',array('model'=>$model));
		} else {
			// $this->redirect("../.."."/pages/index.php");
        	$url = Yii::app()->createUrl('/..'.'/pages/index.php'.'?r=1990');
			$this->redirect($url);
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		// Yii::app()->user->setReturnUrl('../pages/index.php');
		unset(Yii::app()->request->cookies['jlinyii']);
		Yii::app()->user->logout();
		// $this->redirect(Yii::app()->getBaseUrl());
		$this->redirect(Yii::app()->getBaseUrl(true));
	}
}