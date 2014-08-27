<?php
Yii::import('application.components.pageControllers.core.USMFPageController');
/**
 * Controller:
 * 
 */
class JLIFEGodPanel extends USMFPageController
{
	protected function initItemsSublMenu()
	{

		$index_url = '/../pages/index.php';
		return array(
			array('label'=>'Цитаты', 'url'=>array('core/god/quotes', ), 
				),
			array('label'=>'GOD_KO', 'url'=>array('core/god/cr_school', ), 
				'sublitems' => array(
					array('label'=>'Create School', 'url'=>array('/core/god/cr_school',) ),
					array('label'=>'Create Subject', 'url'=>array('/core/god/cr_subject',) ),
					array('label'=>'Create Section', 'url'=>array('/core/god/cr_section',) ),
					),
				),
			array('label'=>'GOD_Teacher', 'url'=>array('', ), 
				),
			array('label'=>'Общее', 'url'=>array('/social/profile/general'),
				'sublitems' => array(array('label'=>'Общее', 'url'=>array('/social/profile/general')), ),
			),
		);
	}
}