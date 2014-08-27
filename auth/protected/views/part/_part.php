<?php
echo 'part';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';

	echo CHtml:: form('');

	echo Chtml::textField('text','valet');


	echo CHtml::dropDownList('drop','', CHtml::listData($models, 'id', 'login'), array('class'=>'classone'));


	echo CHtml:: submitButton('отпр');

	echo CHtml:: endForm();
?>