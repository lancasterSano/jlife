<?php
	// echo "<br/>>>>";
	// print_r($answer_ajax['lastID']);
	// echo "<br/><br/>>>>>";
	// print_r($answer_ajax['logrows']);
	// echo "<<<<br/>";
	// echo "<br/>";
if(isset($answer_ajax))
{
	// var_dump($answer_ajax);
	foreach ($answer_ajax['logrows'] as $key => $YNScenarioItem) {
		// $data = $YNScenarioItem->attributes;
		$data = $YNScenarioItem;

		// echo "<b>";
		// echo $data->ns_groupunic_id." ";
		// echo $data->ns_task." ";
		// echo $data->number." ";
		// echo $data->nameopp." ";
		// echo $data->datetime." ";
		// echo $data->desc." ";
		// // echo $data->debug_backtrace." ";
		// echo $data->success." ";
		// echo $data->date." ";
		// echo "</b><br/>";
		addmsg($data->ns_groupunic_id, $data->ns_task, $data->number, "new", $data->nameopp);
		// echo "</b><br/><br/>";


	}
}

function addmsg($groupunic_id, $task, $number, $type, $msg){
    /* Simple helper to add a div.
    type is the name of a CSS class (old/new/error).
    msg is the contents of the div */
    $incr = 0;
    if($number<10) $value = 0;
    else if($number<20) $value = 1; 
    else if($number<30) $value = 2;
    else if($number<40) $value = 3;
    else if($number>=40) {
        $value = 4;

        $incr = $number%40;
    }

    $padding = "padding-left:" . (10+$value*10+$incr*20) . "px;";
    $background = $number ? "background-color:#1999;" : "";
    $color = $number ? "color:#900;" : "";
    $plh_groupunic_id = $number ? "<b>" . $groupunic_id . "</b>" : "";
    $plh_task = $number ? "<b>" . $task . "</b>" : "";
    $plh_number = $number ? "<b>" . $number . "</b>" : "";
	
	echo CHtml::openTag('div', array('class'=>'msg '.$type, 'style'=>$background));
		echo $plh_groupunic_id . $plh_task;
		echo CHtml::openTag('div', array('class'=>'', 'style'=>'display: -webkit-inline-box;'.$padding . $color));
			echo $plh_number . $msg;
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');

}
?>