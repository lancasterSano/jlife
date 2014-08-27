<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
$smarty->assign('block_css', "style_404");
$pageTitle = 'Ошибка 404';

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$_404_tpl = $smarty->fetch("./mainContent/mainfield/404.tpl");
$smarty->assign('block_mainContent', $_404_tpl);

/************ запускаем показ шаблона smarty *************/
$smarty->display("page.tpl");
?>
