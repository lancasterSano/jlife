<?php
/* @var $this GodController */

?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>
 
    <?php echo $form->errorSummary($model); ?>
 
    <div class="row">
        <?php echo $form->label($model,'number'); ?>
        <?php echo $form->textField($model,'number') ?>
    </div>
    <div class="row submit">
        <?php echo CHtml::submitButton('Создать'); ?>
    </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->



<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
            'focus'=>array($model,'name'),
            // 'id'=>'qa-form',
            'enableAjaxValidation'=>true,
            'clientOptions'=>array('validateOnSubmit'=>true, ),
    )) ?>
 
    <?php echo $form->errorSummary($model, 'Исправьте следующие ошибки:'); ?>
 
    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model,'name') ?>
    </div> 
    <div class="row">
        <?php echo $form->label($model,'number'); ?>
        <?php echo $form->textField($model,'number') ?>
    </div> 
    <div class="row">
        <?php echo $form->label($model,'description'); ?>
        <?php echo $form->textField($model,'description') ?>
    </div>

    <div class="row submit">
        <?php echo CHtml::submitButton('Создать'); ?>
        <?php echo CHtml::ajaxButton('Create AJAX', array('cr_school'), array('update'=>'#quote-of-the-day')); ?>
        <?php echo CHtml::ajaxLink('Next Old', array('getQuote'), array('update'=>'#quote-of-the-day')); ?>
    </div>
 
<?php $this->endWidget(); ?>
</div><!-- form -->