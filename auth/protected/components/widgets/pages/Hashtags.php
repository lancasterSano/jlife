<?php
/**
 * Блок с хештегами.
 * Используется для контроллера FAQ
 * 
 * @author Chereshnya Roman
 */
class Hashtags extends CWidget {
	public $htmlOptions=array();
	public $items=array();
	public function run() {
		if ($this->controller->id === 'faq' && $this->controller->findPageNumber) {
			$this->render('hashtags',array());
		}
	}
}

// end of Hashtags.php