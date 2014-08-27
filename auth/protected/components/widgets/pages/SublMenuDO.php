<?php

class SublMenuDO extends CWidget
{
	public function init() {
        $this->prepareDataMenu();
	}

	public function run() {
		$this->widget('application.components.widgets.JLIFE.jLSublMenu',array(
		    'htmlOptions' => array( 'id' => 'lineTabs'),
		    'items' => Yii::app()->controller->sublmenu,
		    'activateParents'=>true,
		    'activateItems'=>true,
		));
	}
}