<h2>Цитаты дня</h2>
<div id="quote-of-the-day">
	<?php $this->renderPartial('_quote', array('quote'=>$quote)) ?>
</div>
<?php echo CHtml::ajaxLink('Next Old', array('getQuote'), array('update'=>'#quote-of-the-day')); ?>
<?php echo CHtml::ajaxLink('Next', array('getQuote'), array('success'=> new CJavaScriptExpression('function(data){alert(data)}'), ));
?>

<?php $this->widget("ext.facebook_events.EFacebookEvents", array('keyword'=>'php',)) ?>
<?php $this->widget("ext.facebook_events.EFacebookEvents", array('keyword'=>'jquery',)) ?>