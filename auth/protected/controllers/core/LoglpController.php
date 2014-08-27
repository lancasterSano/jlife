<?php

/**
 * 
 */
class LoglpController extends JLIFESocialUSMF
{
	public function beforeAction($action) {
	    if( parent::beforeAction($action) ) {
			$baseUrl = Yii::app()->baseUrl;
			$cs = Yii::app()->clientScript;
	        $cs->registerCssFile($baseUrl.'/css/core/loglp.css');

			$cs->registerScriptFile($baseUrl.'/js/lib/lib_jsmt/jsmt.js' );

			$cs->registerScriptFile($baseUrl.'/js/lib/jquery.tabelizer-master/jquery.tabelizer.min.js' );
	        $cs->registerCssFile($baseUrl.'/js/lib/jquery.tabelizer-master/tabelizer.min.css');
			
			// Add mousewheel plugin (this is optional)
			// "../lib/jquery.mousewheel-3.0.6.pack.js"

			// Add fancyBox main JS and CSS files
			$cs->registerScriptFile($baseUrl.'/js/lib/fancyapps-fancyBox-18d1712/source/jquery.fancybox.js?v=2.1.5' );
	        $cs->registerCssFile($baseUrl.'/js/lib/fancyapps-fancyBox-18d1712/source/jquery.fancybox.css?v=2.1.5');
			// "../source/jquery.fancybox.js?v=2.1.5"
			// "../source/jquery.fancybox.css?v=2.1.5" media="screen"

			// Add Button helper (this is optional)
			// "../source/helpers/jquery.fancybox-buttons.css?v=1.0.5"
			// "../source/helpers/jquery.fancybox-buttons.js?v=1.0.5"

			// Add Thumbnail helper (this is optional)
			// "../source/helpers/jquery.fancybox-thumbs.css?v=1.0.7"
			// "../source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"

			// Add Media helper (this is optional)
			// "../source/helpers/jquery.fancybox-media.js?v=1.0.6"
	        return true;
	    }
	    return false;
	}
	public function actionNight()
	{
		set_time_limit(0);
		$NSS = new NightScenarioStruct;
		$NSS->run();
		set_time_limit(30);
		$this->render('night');
	}
    public function actionList()
    {
    	$baseUrl = Yii::app()->baseUrl;
    	$cs = Yii::app()->clientScript;
    	$cs->registerScriptFile($baseUrl.'/js/loglp.js' );
    	$cs->registerScript('234','alert()',
    		// $baseUrl.'/js/tmpl/_loglp_internal.tpl', 
    		CClientScript::POS_HEAD, 
    		array('type'=>'text/js', 'id'=>'_tmpl_internal')
    	);

			$lastID = 0;
			$data = array(); $repeat=true;
			$NSS = new NightScenarioStruct;
				$logrows = $NSS->existNewLog($lastID);
			
			$datecreate = date("Y-m-d H:i:s");
				$datevalid = new DateTime($datecreate);
				$datevalid->modify('-1 day');
				$datevalid = date_format($datevalid, "Y-m-d H:i:s");
			
			if(is_array($logrows) && count($logrows)) {
				$lastID = $logrows[count($logrows)-1]->id;
				$arr = array('logrows'=>$logrows, 'lastID'=>$lastID);
			}
			else $arr = array('logrows'=>array(), 'lastID'=>$lastID);

			$data['response'] = $arr;
			
			$this->render('list', array('answer_ajax'=>$data['response']));
			
			Yii::app()->end();
    }

    public function actionData()
    {
    	$errors = array();
    	// var_dump($_GET);
    	$r = Yii::app()->getRequest();

		if($r->getIsAjaxRequest())
		{
			$lastID = $r->getQuery('last');
			$data = array(); $repeat=true;

			$errors['lastID'] = $lastID;
			
			$NSS = new NightScenarioStruct;
				$logrows = $NSS->existNewLog($lastID);
			
			$datecreate = date("Y-m-d H:i:s");
				$datevalid = new DateTime($datecreate);
				$datevalid->modify('-1 day');
				$datevalid = date_format($datevalid, "Y-m-d H:i:s");
			
			if(is_array($logrows) && count($logrows)) {
				$lastID = $logrows[count($logrows)-1]->id;
				$arr = array('logrows'=>$logrows, 'lastID'=>$lastID);
			}
			else $arr = array('logrows'=>array(), 'lastID'=>$lastID);

			// $data['response'] = CJSON::encode(CJSON::decode(CJSON::encode($arr)));
			$data['errors'] = $errors;
			$data['data'] = $arr;
			echo CJSON::encode($data);
		}
    }

}