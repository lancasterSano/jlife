<?php
/**
 * Controller:
 * 
 */
class Controller extends CController
{
	protected $pageNumber = null;
	protected $pageName = null;

	protected $pageTitle = 'jLife ';

	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
	        $cs = Yii::app()->clientScript;
	        Yii::app()->getClientScript()->registerCoreScript('jquery'); 
	        
	        $this->pageNumber = NumbersPages::NumberPageByRoute(
	        	$this->id.'/'.$this->action->id
        	);
        		
        	$this->pageTitle .= ' '.$this->pageName;
	        return true;
	    }
	    return false;
	}

}