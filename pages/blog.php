<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "blog");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);




if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
$p1 = $DB->getRow(QS::$q2, $ProfileID);
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Блог '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);



// Получаем ФИО владельца блога
$author = $ProfileLoad->FI();	
$smarty->assign('author',$author);

// Получаем ID владельца страницы для корректной отрисовки кнопки "Удалить"
$idLoad = $ProfileLoad->ID;		
$smarty->assign('idLoad',$idLoad);

// Получить ID авторизированного профиля корректной отрисовки кнопки "Удалить"
$idAuth = $ProfileAuth->ID;
$smarty->assign('idAuth',$idAuth);


//Делаем запрос на все статьи блога (текст, дата, количество лайков, количество комментариев, и.т.д.)
/*
$p = $DB->getAll(QS::$getAllPosts, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_ENTITY, 						$ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_ENTITY);
*/
$p = $DB->getAll(QS::$getAllPosts, $ProfileLoad->ID, SETTING_COUNT_FIRST_LOAD_ENTITY);

//Запускаем цикл по всем найденым статьям блога 
foreach($p as $key => $value) // array_reverse($p) - для реверсирования  прохождения массива
{  
   // Делаем запрос на метки в статье (название метки, цвет и идентификатор)
   $p2 = $DB->getAll(QS::$getMetkas, $ProfileLoad->ID, $ProfileLoad->ID, $value["id"]);

   // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
   $like = $DB->getOne(QS::$getLikeBlog, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $value["id"]);

   // Запускаем цикл по найденым меткам
	foreach($p2 as $key => $value1)
   {
		// Сохраняем первую найденную метку в массив $oneMetka
		$oneMetka = array(
			"idblogmetka" => $value1["idblogmetka"],
			"name" => $value1["name"],
			"color" => $value1["color"]
		);
	// Помещаем массив $oneMetka в массив $spisokMetok
	$spisokMetok[$oneMetka["idblogmetka"]] = $oneMetka;
	}
	// Сохраняем в переменную $link значение "source" (источник информации в статье)
	$link = $value["source"];
	// Проверяем, если длина строки переменной $link больше, чем 23 символов...
	if(mb_strlen($link) > 23)
		// Тогда переприсваиваем значение переменной $link, укорачивая строку до 23 символов от начала и дописываем "..."
		$link = mb_substr($link, 0, 23)."...";
	// Присваиваем массиву $content значения, которые мы получили из запроса к статьям блога (текст, дата, количество лайков, и.т.д.) 
	$content = array(
				   "id" => $value["id"],
				   "text" => $value["text"],
				   "datetime" => $value["datetime"],
				   "idauthor" => $value["idauthor"],
				   "countlike" => $value["countlike"],
				   "countcomment" => $value["countcomment"],
				   "isProfileAuthSetLike" => $like != null ? ($like["count"]>0 ? true:false) : null,
				   "extension" => $value["extension"],
				   "name" => $value["name"],
				   "source" => $value["source"],
				   "nameLink" => $link,
					// Присваиваем массиву $content массив $spisokMetok
				   "metkas" => $spisokMetok
   );

	if($content["extension"] == null)
			{                                              
				$contents[$value["id"]] = $content;
				$root_content = $content;
					$last_add_content = $content;           
			}
	else if($content["extension"] != null)
			{
				$contents[$root_content["id"]]["text"] .= $content["text"];
			}
   // Обнуляем массив $spisokMetok
   unset($spisokMetok);
   // Присваиваем массиву $contents массив $content
   		//$contents[$value["id"]] = $content;
   // Обнуляем массив $content
   unset($content);
}

//if($contents != null)
	//krsort($contents);
//print_r($contents);

// Делаем запрос на вывод в combobox только существующих меток на блоге
$p = $DB->getAll(QS::$getMetkasInBox, $ProfileLoad->ID);
// Запускаем цикл по всем найденым меткам
foreach($p as $key => $value)
{
		// Сохраняем первую найденную метку в массив $oneMetka
		$oneMetka = array(
			"idblogmetka" => $value["idblogmetka"],
			"name" => $value["name"],
			"color" => $value["color"]
		);
	// Помещаем массив $oneMetka в массив $spisokMetok
	$spisokMetok[$oneMetka["idblogmetka"]] = $oneMetka;
}
//print($ProfileLoad->countblog);
//print_r($contents);
//print_r($spisokMetok);
$smarty->assign('spisokMetok',$spisokMetok);
$smarty->assign('source',$source);
$smarty->assign('contents',$contents);


/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");

$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 21);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_blog.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionBlogSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/blog.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>