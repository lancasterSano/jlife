<?php
Yii::import('application.components.pageControllers.core.Controller');
/**
 * Controller:
 * 
 */
class PageSimpleController extends Controller
{
	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
	    	$baseUrl = Yii::app()->baseUrl;
	        $cs = Yii::app()->clientScript;
	        // $cs->registerScriptFile($baseUrl.'/js/DOM.js' );
	        // $cs->registerScriptFile($baseUrl.'/js/common.js' );
	        // $cs->registerScriptFile($baseUrl.'/js/sidebar.js' );
	        // $cs->registerScriptFile($baseUrl.'/lib/lib_jsmt/jsmt.js' );
	        // $cs->registerScriptFile($baseUrl.'/js/settings/settings.js' );
	        return true;
	    }
	    return false;
	}

}