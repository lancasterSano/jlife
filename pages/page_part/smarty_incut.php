<?php
// /* 1 debug ===> */ 			$smarty->assign('debug_context', $debug_context);
/* 2 timeout update ===> */	$timeout_js = $smarty->fetch("./general/timeout_js.tpl");  $smarty->assign('timeout_context', $timeout_js);
?>