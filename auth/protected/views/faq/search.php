<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div class="searchRes">
  <span>
    Найдено 4 результата: для 
    <?php
      if(property_exists(get_class($this), 'findPageNumber')) echo '<b>'.$this->findPageNumber.'</b>';      
    ?>
  </span>
</div>
<div class="accordionContainer">    
  <?php

	$questions = array();
	foreach ($model->search()->getData() as $key => $value) {
		// var_dump($value);
		$questions[$value->title] = $this->renderPartial('_answer_partial',array('answers'=>$value->answers),true);
	}

    $this->widget('zii.widgets.jui.CJuiAccordion', array(
      'theme'=>'redmond',
      'panels'=> $questions,
      // дополнительные javascript-опции для плагина блока-аккордеона
      'options'=>array(
        // 'animated'=>'bounceslide',
        'collapsible'=>true,
        'active'=> (count($questions)==1) ? 0 : false,
        'icons'=>null,
        'heightStyle'=>"content",
      ),
      // 'htmlOptions'=>array('class'=>'accordion'),
    ));
  ?>


<?php 
// $this->widget('zii.widgets.grid.CGridView', array(
// 	'id'=>'tbl-user-grid',
// 	'dataProvider'=>$model->search(),
// 	'filter'=>$model,
// 	// 'columns'=>array(
// 	// 	'id',
// 	// 	'username',
// 	// 	'password',
// 	// 	'email',
// 	// 	array(
// 	// 		'class'=>'CButtonColumn',
// 	// 	),
// 	// ),
// )); 
?>

<?php
// $this->widget('zii.widgets.CListView', array(
// 	'dataProvider'=>$model->search(),
// 	'itemView'=>'_view',
// 	'pager'=>array('cssFile'=>'/css/mypager.css'),
// 	'summaryText'=>'',//'Result {start} - {end} of {count} results'
// 	'pager' => Array(
// 	        'cssFile'=>Yii::app()->theme->baseUrl."/css/pagination.css",
// 	        'header' => 'Go To Page',
// 	        'prevPageLabel' => 'Previous',
// 	        'nextPageLabel' => 'Next',
// 	        'firstPageLabel'=>'First',
// 	        'lastPageLabel'=>'Last'
// 	),
// )); 
?>
</div>