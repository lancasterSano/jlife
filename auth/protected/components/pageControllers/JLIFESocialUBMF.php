<?php
Yii::import('application.components.pageControllers.core.UBMFPageController');
/**
 * Controller:
 * 
 */
class JLIFESocialUBMF extends UBMFPageController
{
	protected function initItemsSublMenu()
	{
		$index_url = '/../pages/index.php';
		return array(
			array('label'=>'Заметки', 'url'=>array('/../pages/index.php', ),
			'sublitems' => array(
				array('label'=>'Все заметки', 'url'=>array($index_url)),
				array('label'=>'Мои заметки', 'url'=>array($index_url))
				),
			),
			array('label'=>'Общее', 'url'=>array('/social/profile/general'),
				'sublitems' => array(
					array('label'=>'Общее', 'url'=>array('/social/profile/general')),
					),
				),
		);
	}
}