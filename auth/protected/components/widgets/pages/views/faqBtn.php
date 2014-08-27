<?php
	echo CHtml::openTag('div',$this->htmlOptions)."\n";
		$cur_request_path_info = Yii::app()->getRequest()->getPathInfo();
		
		$cur_page_muber = $this->controller->pageNumber;
		$actionRole = ROLESTYPES::getEnLabaelByRouteType($this->controller->selectRoleRouteType);

		$url=Yii::app()->createUrl( $cur_request_path_info , array( 'faq'=>''));

		echo CHtml::link('?', $url, array('id' => 'faqp-link', 'title'=>"Справка"));
	echo CHtml::closeTag('div');
?>
