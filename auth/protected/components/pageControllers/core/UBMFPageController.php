<?php
Yii::import('application.components.pageControllers.core.PageController');
/**
 * Controller:
 * UBMF layuout
 * global UBMF style page
 */
class UBMFPageController extends PageController
{
	public $layout='//layouts/SocialUBMF';
	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
	    	$baseUrl = Yii::app()->baseUrl;
	        $cs = Yii::app()->clientScript;
	        $cs->registerCssFile($baseUrl.'/css/style.css');
	        return true;
	    }
	    return false;
	}
}