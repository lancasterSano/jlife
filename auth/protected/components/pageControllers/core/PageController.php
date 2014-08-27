<?php
Yii::import('application.components.pageControllers.core.Controller');
/**
 * Controller:
 * 
 */
class PageController extends Controller
{
	protected $menu=array();
	protected $breadcrumbs=array();
	/**
	 * requariament subl menu items
	 */
	protected $pagesOrder = null;
	/**
	 * subl menu items as CMenu template
	 */
	protected $sublmenu = null;

	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
	    	$baseUrl = Yii::app()->baseUrl;
	        $cs = Yii::app()->clientScript;
	        $cs->registerCssFile($baseUrl.'/css/header.css');
	        $cs->registerScriptFile($baseUrl.'/js/DOM.js');
	        $cs->registerScriptFile($baseUrl.'/js/common.js');
	        $cs->registerScriptFile($baseUrl.'/js/ll/gn.pp.js');

	        $cs->scriptMap=array(
	            // '/js/DOM.js'=>'/js/all.js',
	            // 'jquery.ajaxqueue.js'=>'/js/all.js',
	            // 'jquery.metadata.js'=>'/js/all.js',
	            // …
	        );

	        $this->pagesOrder = $this->initPagesOrderSublMenu();
	        $this->sublmenu = $this->initItemsSublMenu();
	        $this->afterInitItemsSublMenu();
	        return true;
	    }
	    return false;
	}
	/**
	 * Method initialization requariament for subl menu items on page
	 */
	protected function initPagesOrderSublMenu()
	{
		return array();
	}
	/**
	 * Method initialization subl menu items as CMenu template
	 */
	protected function initItemsSublMenu()
	{
		return array();
	}
	/**
	 * Method exec before initItemsSublMenu
	 */
	protected function beforeInitItemsSublMenu() {}
	/**
	 * Method exec after initItemsSublMenu
	 */
	protected function afterInitItemsSublMenu() {}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
		);
	}
	public function accessRules()
	{
		return array(
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array('index','view'),
			// 	'users'=>array('*'),
			// ),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array('create','update'),
			// 	'users'=>array('@'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('admin','delete'),
			// 	'users'=>array('admin'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('guest'),
			// 	'users'=>array('?'),
			// ),
			// array('deny',  // deny all users
			// 	'users'=>array('*'),
			// ),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
}