<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['countContinuation']))   { $countContinuation=$_POST['countContinuation'];}
if (isset ($_POST['idBlogLast']))   { $idBlogLast=$_POST['idBlogLast'];}
if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}
if (isset ($_POST['idMetka']))   { $idMetka=$_POST['idMetka'];}

//$p = $DB->getRow(QS::$q3, $idProfLoad);
//$ProfileLoad = new Profile($idProfLoad, $p);

//$author = $ProfileLoad->FIO();
$i = -1; // Переменная для индексации элементов массива "$contents"
if(isset ($idProfLoad) && isset ($idBlogLast) && isset ($countContinuation) && 
    !empty ($idProfLoad) && !empty ($countContinuation) && !empty ($idBlogLast))
{
    if($idBlogLast != 'null' && $idMetka == 0)
    /*
        $q = $DB->getAll(QS::$getLoadedPosts, $idProfLoad, $idProfLoad, $idBlogLast, $countContinuation,
                                              $idProfLoad, $idProfLoad, $idBlogLast, $countContinuation,   
                                              $idProfLoad, $idBlogLast, $countContinuation);
    */
        $q = $DB->getAll(QS::$getLoadedPosts, $idProfLoad, $idProfLoad, $idBlogLast, $countContinuation);

    else if($idBlogLast != 'null' && $idMetka == -1)
    /*
        $q = $DB->getAll(QS::$getLoadedWithoutMetkaPosts, $idProfLoad, $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad,
                                                          $countContinuation,
                                                          $idProfLoad, $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad,
                                                          $countContinuation,
                                                          $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad,
                                                          $countContinuation);
    */
        $q = $DB->getAll(QS::$getLoadedWithoutMetkaPosts, $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad,
                                                          $countContinuation);
        
    else if($idBlogLast != 'null' && $idMetka > 0)
    /*
        $q = $DB->getAll(QS::$getLoadedMetkaPosts, $idProfLoad, $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad, $idMetka,
                                                   $countContinuation,
                                                   $idProfLoad, $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad, $idMetka,
                                                   $countContinuation,
                                                   $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad, $idMetka,
                                                   $countContinuation);
    */
        $q = $DB->getAll(QS::$getLoadedMetkaPosts, $idProfLoad, $idBlogLast, $idProfLoad, $idProfLoad, $idMetka,
                                                  $countContinuation);
    /*
    else
        $q = $DB->getAll(QS::$getAllPosts, $idProfLoad, $idProfLoad, $idProfLoad, $countContinuation, $idProfLoad, 
                            $countContinuation);
    
         $q = $DB->getAll(QS::$getAllPosts, $idProfLoad, $countContinuation);
    */

    if($q)
    {
        foreach($q as $key => $value) // array_reverse($p) - для реверсирования  прохождения массива
        {  
           // Делаем запрос на метки в статье (название метки, цвет и идентификатор)
           $p2 = $DB->getAll(QS::$getMetkas, $idProfLoad, $idProfLoad, $value["id"]);

            // Автор записи
            $authors = $DB->getRow(QS::$q5, $value["idauthor"]);

           // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
           $like = $DB->getOne(QS::$getLikeBlog, $idProfLoad, $idProfLoad, $idProfAuth, $idProfLoad, $value["id"]);
           //echo($idProfLoad + " " + $idProfAuth + " " + $value["id"]);
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
                           "fioAuthor" => $authors["lastname"]." ".$authors["firstname"],
                           "countlike" => $value["countlike"],
                           "countcomment" => $value["countcomment"],
                           "isProfileAuthSetLike" => $like["count"]>0 ? true:false,
                           "extension" => $value["extension"],
                           "name" => $value["name"],
                           "source" => $value["source"],
                           "nameLink" => $link,
                            // Присваиваем массиву $content массив $spisokMetok
                           "metkas" => $spisokMetok
           );

            if($content["extension"] == null)
                    {                                              
                        $i++;
                        $contents[$i] = $content;
                        $root_content = $content;
                            $last_add_content = $content;           
                    }
            else if($content["extension"] != null)
                    {
                        $contents[$i]["text"] .= $content["text"];
                    }
            //echo($i+" | ");
           // Обнуляем массив $spisokMetok
           unset($spisokMetok);
           // Присваиваем массиву $contents массив $content
                //$contents[$value["id"]] = $content;
           // Обнуляем массив $content
           unset($content);
        }       
        $rez = array("loadblog", $contents);
    }else $rez = array("loadblog", null, false); 
} else $rez = array("unknown", null);
print json_encode($rez);
?>