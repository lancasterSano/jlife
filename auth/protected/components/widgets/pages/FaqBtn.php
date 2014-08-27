<?php
/**
 * Выводится ссылка на подсистему справка.
 * 
 * @author Chereshnya Roman
 */
class FaqBtn extends CWidget {
	public $htmlOptions=array();
	public function run() {
		if (preg_match('#^do/#', trim($this->controller->id,'/')) === 1) {
			//показываем виджет
			$this->render('faqBtn',array('items'=>$items, 'monthNames'=>$monthNames));
		}
	}
}

// end of FaqBtn.php