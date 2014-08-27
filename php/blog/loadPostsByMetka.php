<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if(isset($_POST['idMetka']))
{
	$idMetka = $_POST['idMetka'];
}
if(isset($_POST['idOwner']))
{
	$idOwner = $_POST['idOwner'];
}
//echo $idOwner;
//Получаем ФИО автора
$p = $DB->getRow(QS::$q3, $idOwner);
$ProfileLoad = new Profile($idOwner, $p);

$author = $ProfileLoad->FI();


// Проверяем, если выбран пункт "Все метки", то делать другой запрос
if($idMetka == 0)
{	
	/*
	$p = $DB->getAll(QS::$getAllPosts, $idOwner, $idOwner, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY);
	*/
	$p = $DB->getAll(QS::$getAllPosts, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY);
	
	$countblog = $ProfileLoad->countblog;
}
else 
	// Проверяем, если выбран пункт "Без метки", то делать другой запрос
	if($idMetka == -1)
	{
		$p = $DB->getAll(QS::$getPostsWithoutMetka, $idOwner, $idOwner, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY);
		//print_r($p);
		$i = 0; // Переменная помогает определить, в какую часть массива вставлять дочерние строки
		$count = 0; // Переменная помогает при 1 интерации присвоить значению "tempExtension" значение
		$countblog = $DB->getAll(QS::$getCountWithoutMetkaPosts, $idOwner, $idOwner, $idOwner);
		/*
		// Запускаем цикл по найденым статьям по метке, и делаем запрос на поиск дочерних строк статьи
		foreach($p as $key => $value)
		{
			// Делаем запрос на дочерние строки главной строки
			$p3 = $DB->getAll(QS::$getDoughterPosts, $idOwner, $value["id"]);

			// Запускаем цикл по найденым дочерним строкам главной строки
			foreach($p3 as $key => $value1)
			{
				// Условие для присваивания первый раз значения для переменной "tempExtension"
				if($count == 0)
				{
					$tempExtension = $value["id"];
					$count = 1;
				}
				// Условие для определения сдвига номера ячейки для вставки в массив элемента
				if($tempExtension == $value1["extension"])
					$i++;
				else
				{
					$i+=2;
					$tempExtension = $value1["extension"];
				}

			$temp[] = array("id" => $value1["id"],
							"datetime" => $value1["datetime"],
							"text" => $value1["text"],
							"countlike" => $value1["countlike"],
							"countcomment" => $value1["countcomment"],
							"extension" => $value1["extension"],
							"name" => $value1["name"],
							"source" => $value1["source"],
							"idauthor" => $value1["idauthor"]
							);
			array_splice($p,$i,0,$temp);
			unset($temp);
			}
		}
		*/
	}
else
{	//Делаем запрос на статьи блога по выбранной метке (текст, дата, количество лайков, количество комментариев, и.т.д.) 
	$p = $DB->getAll(QS::$getPostsByMetka, $idOwner, $idOwner,$idOwner, $idMetka, SETTING_COUNT_FIRST_LOAD_ENTITY);
	//print_r($p);
	$i = 0; // Переменная помогает определить, в какую часть массива вставлять дочерние строки
	$count = 0; // Переменная помогает при 1 интерации присвоить значению "tempExtension" значение

	// Количество записей с указанной меткой
	$countblog = $DB->getAll(QS::$getCountMetkaPosts, $idOwner, $idOwner, $idOwner, $idMetka);
	//echo $p[0]["id"];
	//print_r($countblog);
	// Запускаем цикл по найденым статьям по метке, и делаем запрос на поиск дочерних строк статьи
	foreach($p as $key => $value)
	{
		// Делаем запрос на дочерние строки главной строки
		$p3 = $DB->getAll(QS::$getDoughterPosts, $idOwner, $value["id"]);
		//print_r($p3[0]["id"]);
		/*
		// Запускаем цикл по найденым дочерним строкам главной строки
		foreach($p3 as $key => $value1)
		{
			// Условие для присваивания первый раз значения для переменной "tempExtension"
			if($count == 0)
			{
				$tempExtension = $value["id"];
				$count = 1;
			}
			// Условие для определения сдвига номера ячейки для вставки в массив элемента
			if($tempExtension == $value1["extension"])
				$i++;
			else
			{
				$i+=2;
				$tempExtension = $value1["extension"];
			}

		$temp[] = array("id" => $value1["id"],
						"datetime" => $value1["datetime"],
						"text" => $value1["text"],
						"countlike" => $value1["countlike"],
						"countcomment" => $value1["countcomment"],
						"extension" => $value1["extension"],
						"name" => $value1["name"],
						"source" => $value1["source"],
						"idauthor" => $value1["idauthor"]
						);
		array_splice($p,$i,0,$temp);
		unset($temp);

		}
		*/
	}
}
$i = -1; // Переменная для индексации элементов массива "$contents"
//Запускаем цикл по всем найденым статьям блога
foreach($p as $key => $value)
{  
   // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
   $like = $DB->getOne(QS::$getLikeBlog, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $value["id"]);

   // Делаем запрос на метки в статье (название метки, цвет и идентификатор)
   $p2 = $DB->getAll(QS::$getMetkas, $idOwner, $idOwner, $value["id"]);
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
				//$contents[$value["id"]] = $content;
				$i++;
				$contents[$i] = $content;
				$root_content = $content;
				//$last_add_content = $content;           
			}
	else if($content["extension"] != null)
			{
				
				//if($content["extention"] == $contents[$root_content["id"]]["id"])
				$contents[$i]["text"] .= $content["text"];
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
	//$countblog = $ProfileLoad->countblog;
	$response = array("contents" => $contents, "author" => $author, "countblog" => $countblog);

	//print_r($contents);
	//print_r($response);
	print json_encode($response);
?>