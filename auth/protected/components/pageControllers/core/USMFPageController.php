<?php
Yii::import('application.components.pageControllers.core.PageController');
/**
 * Controller:
 * USMF layuout
 * global USMF style page
 */
class USMFPageController extends PageController
{
	public $layout='//layouts/SocialUSMF';
	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
	    	$baseUrl = Yii::app()->baseUrl;
	        $cs = Yii::app()->clientScript;
	        $cs->registerCssFile($baseUrl.'/css/style_lim.css');
	        return true;
	    }
	    return false;
	}
}