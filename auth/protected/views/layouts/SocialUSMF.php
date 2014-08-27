<?php $this->beginContent('//layouts/pages/pPage'); ?>
	<?php $this->beginContent('//layouts/unions/unionSMF'); ?>
		<!-- Меню для SOCIAL -->
		<?php
			$this->widget('application.components.widgets.JLIFE.jLSublMenu',array(
		        'htmlOptions' => array( 'id' => 'lineTabs'),
		        'items' => Yii::app()->controller->sublmenu,
		        'activateParents'=>true,
		        'activateItems'=>true,
		    ));
		?>
	<?php $this->endContent(); ?>
	<div class="mainfield">    
	    <div class="content">
	        <?php echo $content; ?>
	    </div>
	</div>
<?php $this->endContent(); ?>