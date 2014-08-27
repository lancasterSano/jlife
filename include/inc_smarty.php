<?php /*********************** подключаем библиотеку smarty ***********************/

define('SMARTY_DIR',PROJECT_PATH.'/lib/lib_smarty/');
//echo PROJECT_PATH.SMARTY_DIR.'Smarty.class.php';
require(SMARTY_DIR.'Smarty.class.php');

$smarty = new Smarty ();//обьект smarty

$smarty->caching = false;




$smarty->template_dir=PROJECT_PATH.'/tpl/templates/';//указываем путь к шаблонам
$smarty->compile_dir=PROJECT_PATH.'/tpl/templates_c/';
$smarty->config_dir=PROJECT_PATH.'/tpl/configs/';
$smarty->cache_dir=PROJECT_PATH.'/tpl/cache/';
?>