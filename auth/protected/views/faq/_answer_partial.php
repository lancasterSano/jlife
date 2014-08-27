<?php
  $answersPanels = array();
  if(is_array($answers) && count($answers)) {

    // $answersPanels = array(
    //   'Как угомонить всю ерунду в Крыму? 1'=>$this->renderPartial('_answer_partial_one',null,true),
    //   );
    foreach ($answers as $index => $objAnswer) {
      $answersPanels[$objAnswer->title.' '.$objAnswer->id] = $this->renderPartial('_answer_partial_one',array('answer'=>$objAnswer),true);
      // $answersPanels[$objAnswer->title.' '.$objAnswer->id] = $objAnswer->body;
    }
  }
  $this->widget('zii.widgets.jui.CJuiAccordion', array(
    'theme'=>'redmond',
    'panels'=>$answersPanels,
    // дополнительные javascript-опции для плагина блока-аккордеона
    'options'=>array(
      // 'animated'=>'bounceslide',
      'collapsible'=>true,
      'active'=> (count($answersPanels)==1) ? 0 : false,
      'icons'=>true,
      'heightStyle'=>"content",
    ),
    'htmlOptions'=>array('class'=>'accordion'),
  ));
  // print_r($answers);
?>

