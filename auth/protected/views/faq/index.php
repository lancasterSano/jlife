<?php
/* @var $this FaqController */

$this->breadcrumbs=array(
	'Faq',
);
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
    $this->widget('zii.widgets.jui.CJuiAccordion', array(
      'theme'=>'redmond',
      'panels'=>array(
        'заголовок панели 1'=>'содержимое панели 1',
        'заголовок панели 2'=>'содержимое панели 2',
        // содержимое панели 3 генерируется частичным представлением
        'заголовок панели 3'=>$this->renderPartial('_answer_partial',null,true),
      ),
      // дополнительные javascript-опции для плагина блока-аккордеона
      'options'=>array(
        // 'animated'=>'bounceslide',
        'collapsible'=>true,
        'active'=>false,
        'icons'=>null,
        'heightStyle'=>"content",
      ),
      'htmlOptions'=>array('class'=>'accordion'),
    ));
  ?>
<?php echo $this->renderPartial('_debug_data_partial',null,true); ?>
<?php
  // $this->beginWidget('zii.widgets.jui.CJuiDialog', 
  //     array(
  //         'id'=>'mydialog',
  //         // дополнительные javascript-опции для плагина диалогового окна
  //         'options'=>array(
  //             'title'=>'Заголовок диалогового окна',
  //             'autoOpen'=>false,
  //       ),
  //     'htmlOptions'=>array(
  //         'class'=>'dialog'
  //      ),
  // ));
   
  //     echo 'Содержимое диалогового окна';
   
  // $this->endWidget('zii.widgets.jui.CJuiDialog');
   
  //     // ссылка, по которой можно открыть диалоговое окно
  //     echo CHtml::link('open dialog', '#', array(
  //         'onclick'=>'$("#mydialog").dialog("open"); return false;',
  //     ));
?>
<?php
// //================ v.1
// $this->widget('zii.widgets.jui.CJuiButton', 
//     array(
//         'name'=>'submit',
//         'caption'=>'Сохранить',
//         'options'=>array(
//             'onclick'=>'js:function(){alert("Да");}',
//         ),
//     )
// );
// //================ v.2
// $this->widget('zii.widgets.jui.CJuiButton',
//     array(
//         'name'=>'button',
//         'caption'=>'Click on me!',
//         'value'=>'abc',
//         'htmlOptions'=>array(
//             'style'=>'height:40px;',
//             'class'=>' button'
//         ),
//         'onclick'=>'js:function(){alert("Да!"); this.blur(); return false;}',
//     )
// );
?>
<?php 
  // //================ v.1
  // $this->widget('zii.widgets.jui.CJuiSliderInput', array(
  //       'name'=>'rate',
  //       'value'=>37,
  //       // дополнительные javascript-опции для плагина слайдера
  //       'options'=>array(
  //           'min'=>10,
  //           'max'=>50,
  //       ),
  //       'htmlOptions'=>array(
  //           'style'=>'height:20px;',
  //            'class'=>'slider'
  //       ),
  //   ));
  // //================ v.2
  // $this->widget('zii.widgets.jui.CJuiSliderInput', array(
  //       'model'=>$model,
  //       'attribute'=>'timeMin',
  //       'maxAttribute'=>'timeMax',
  //       // дополнительные javascript-опции для плагина слайдера
  //       'options'=>array(
  //           'range'=>true,
  //           'min'=>0,
  //           'max'=>24,
  //       ),
  //   ));
?>
<?php
// $this->widget('zii.widgets.jui.CJuiSlider', array(
//       'value'=>37,
//       // дополнительные javascript-опции для плагина слайдера
//       'options'=>array(
//           'min'=>10,
//           'max'=>50,
//           'slide'=>'js:function(event, ui) { $("#id").val(ui.value);}', 
//       ),
//       'htmlOptions'=>array(
//           'style'=>'height:20px;',
//           'class'=>'slider'
//       ),
//   ));
?>
<?php 
  // $this->widget('zii.widgets.jui.CJuiProgressBar', array(
  //       'value'=>75,
  //       // дополнительные javascript-опции для плагина прогресс-бара
  //       'options'=>array(
  //           'change'=>'js:function(event, ui) {...}',
  //       ),
  //       'htmlOptions'=>array(
  //           'style'=>'height:20px;',
  //           'class'=>'progressbar'
  //       ),
  //   ));
?>
<?php
// $this->widget('zii.widgets.jui.CJuiTabs', array(
//      'tabs'=>array(
//          'Статичный таб 1'=>'Содержимое первого таба',
//          'Статичный таб 2'=>array('content'=>'Содержимое второго таба', 'id'=>'tab2'),
//          // содержимое третьей панели генерируется частичным представлением
//          'AjaxTab'=>array('ajax'=>$ajaxUrl),
//      ),
//      // дополнительные javascript-опции для плагина табов
//      'options'=>array(
//          'collapsible'=>true,
//      ),
//      'htmlOptions'=>array('class'=>'tabs'),
//   ));
?>
</div>