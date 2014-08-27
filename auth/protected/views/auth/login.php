<?php
/* @var $this AuthController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
?>
<?php
$this->pageTitle=Yii::app()->name . ' : Авторизация';
// $this->breadcrumbs=array(
// 	'Login',
// );
 // echo "<br/><b>Yii returnUrl: </b>".(Yii::app()->user->returnUrl)."<br/><br/><br/><br/><br/>";
?>       
<div style='float:left;font-weight:bold;color:#8eb4e3;font-size:24px;margin-top:469px;margin-left:110px;'>
        <span style='color:red;float:left;'>J</span><span>Life</span>
        <span style='font-weight:normal;'>- Ваша интеллектуальная сеть</span>
    </div> 
<div class="registration">
	<div class="enter"><span>Войти</span></div>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>
        <div class="RegistrFields">
		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username'); ?>
			<div class="errorMessageCont">
			<?php echo $form->error($model,'username'); ?>
			</div>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<div class="errorMessageCont">
			<?php echo $form->error($model,'password'); ?>
			</div>
		</div>

		<div class="row rememberMe" style="display:none;">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<div class="errorMessageCont">
			<?php echo $form->error($model,'rememberMe'); ?>
			</div>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Login'); ?>
		</div>
		</div>

	<?php $this->endWidget(); ?>
</div>



