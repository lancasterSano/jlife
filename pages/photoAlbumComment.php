<?php 
// require_once '.htpaths';
// require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
// require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
// require_once(PROJECT_PATH."/php/common.php");
// require_once(PROJECT_PATH."/php/class/QueryStorage.php");
// require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
// require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
// require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');


// /**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

// $ProfileLoad = $UA->getProfile();
// $smarty->assign('PROJECT_PATH', PROJECT_PATH);
// $smarty->assign('is_do', 0); 
// require_once(PROJECT_PATH."/pages/development.php");

# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "photoAlbumComment");
$smarty->assign('block2_page_sys', "friends");
$smarty->assign('block3_page_sys', "subscribers");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



if(isset($_GET['id']) && $_GET['id']>0) $ProfileID = $_GET['id']; else $ProfileID = $ProfileAuth->ID;
# Exist Profile(Profile) by [ID]
$p = $DB->getRow(QS::$q3, $ProfileID);
if(!isset($p)) {
	$params = ((isset($ProfileID))?"?l=".$ProfileID:"");
 	echo header("Location: ".PROJECT_PATH."/pages/absent.php".$params); exit(); 
}
$ProfileLoad = new Profile($ProfileID, $p);

$smarty->assign('PageTitle', 'Коментарии'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

# Получаем ID владельца страницы для корректной отрисовки кнопки "Удалить"
$idLoad = $ProfileLoad->ID;     
$smarty->assign('idLoad',$idLoad);

# Получить ID авторизированного профиля корректной отрисовки кнопки "Удалить"
$idAuth = $ProfileAuth->ID;
$smarty->assign('idAuth',$idAuth);

# Друг ли авторизованный пользователь и Владелец загружаемой страницы
$friend = $DB->getOne(QS::$qfriend1, $ProfileAuth->ID, $ProfileLoad->ID);
$smarty->assign('friend', $friend);


# Поиск 20-ти последних комментариев к фотографиям
$lastCommentsPhotoesQuery = $DB->getAll(QS::$getLastCommentsPhoto, $ProfileID, $ProfileID,
                                                   5);

    foreach($lastCommentsPhotoesQuery as $key => $valueComment)    
    {
        
        // Делаем запрос на получение полной информации об авторе комментария
        $authorInfo = $DB->getRow(QS::$q3, $valueComment["profile_id"]);
        // Создаем новый объект автора комментария
        $authorComment = new Profile($valueComment["profile_id"], $authorInfo);
        
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
        $like_com = $DB->getOne(QS::$getLikeCommentPhoto, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $valueComment["id"]);

        // id комента на который отвечают
        $answerCommentID = $valueComment["commentspisokalbumphoto".$ProfileLoad->ID."_id"];
        //print_r($answerCommentID);
        if($answerCommentID != null)
        {  
            // Делаем запрос на получение id автора, которому адресован комментарий
            $answerProfile = $DB->getRow(QS::$getProfileId_2, $ProfileLoad->ID, $answerCommentID);
            // Получаем id автора
            $answerProfileID = $answerProfile["profile_id"];
            // Делаем запрос на получение информации об авторе, которому адресован комментарий
            $author_comment_special = $DB->getRow(QS::$getProfileFI_2, $answerProfileID);
            // Получаем Имя и Фамилию автора, к которому адресован комментарий
            $answerCommentFIO = $author_comment_special["lastname"]." ".$author_comment_special["firstname"];
            // полное обращение
            preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueComment["text"], $matches);
            // Обращение безя запятой
            if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
            // текст без обращения и запятой
            $valueComment["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueComment["text"]);
        }

        $dateTimeComment = setRussianDate(date("j n Y H i", strtotime($valueComment["datetime"])));
        $comment = array(
            "idCommentPhoto" => $valueComment["id"],
            //"adress" => $matches_adress[0],
            "datetime" => $valueComment["datetime"],
            "datetimeToShow" => $dateTimeComment,
            "datetimeInSeconds" => strtotime(date($valueComment['datetime'])),
            "text" => $valueComment["text"],
            "countLike" => $valueComment["countlike"],
            "spisokAlbumPhotoNNN_id" => $valueComment["spisokalbumphoto".$ProfileLoad->ID."_id"],
            "commentSpisokAlbumPhotoNNN_id" => $valueComment["commentspisokalbumphoto".$ProfileLoad->ID."_id"],
            "authorCommentID" => $valueComment["profile_id"],
            "authorCommentFIO" => $authorComment->FI(),
            "authorCommentPathAvatar" => PROJECT_PATH.$authorComment->ProfilePathAvatar(),
            "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
            "isProfileOwnerComment" => ($authorComment->ID == $idAuth ? true:false),
            "answerCommentId" => $answerCommentID,
            "answerAuthorCommentFIO" => $answerCommentFIO,
            "answerAuthorID" => $answerProfileID,
            "extension" => $valueComment["extension"]
        );
        if($comment["extension"] == null)
                {                                              
                    $commentsPhotoTemp[$valueComment["id"]]=$comment;
                    $root_comment = $comment;
                    //$last_add_comment = $comment;
                }
        else if($comment["extension"] != null)
                {
                    $commentsPhotoTemp[$root_comment["idCommentPhoto"]]["text"] .= $comment["text"];
                }
    }

    $countCommentsPhoto = count($commentsPhotoTemp);
    // print_r($commentsPhotoTemp);exit();


$lastCommentsAlbumsQuery = $DB->getAll(QS::$getLastCommentsAlbum, $ProfileID, $ProfileID, 5);

$Album = array(
        "idPhotoAlbom" => '15',  // id
        "namePhotoAlbom" => 'Вот так вот',  // name
		"countPhoto" =>'20' ,  //  
        "countLikePhotoAlbom" =>'50', //   countlike
        "isProfileAuthSetLike" =>'',
    );
    
    $smarty->assign('Album', $Album);
foreach($lastCommentsAlbumsQuery as $key => $valueComment)    
    {
        
        // Делаем запрос на получение полной информации об авторе комментария
        $authorInfo = $DB->getRow(QS::$q3, $valueComment["profile_id"]);
        // Создаем новый объект автора комментария
        $authorComment = new Profile($valueComment["profile_id"], $authorInfo);
        
        // Поставил ли АВТОРИЗИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ лайк на комменте записи
        $like_com = $DB->getOne(QS::$getLikeCommentAlbum, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $valueComment["id"]);

        // id комента на который отвечают
        $answerCommentID = $valueComment["commentalbum".$ProfileLoad->ID."_id"];
        //print_r($answerCommentID);
        if($answerCommentID != null)
        {  
            // Делаем запрос на получение id автора, которому адресован комментарий
            $answerProfile = $DB->getRow(QS::$getProfileId_2, $ProfileLoad->ID, $answerCommentID);
            // Получаем id автора
            $answerProfileID = $answerProfile["profile_id"];
            // Делаем запрос на получение информации об авторе, которому адресован комментарий
            $author_comment_special = $DB->getRow(QS::$getProfileFI_2, $answerProfileID);
            // Получаем Имя и Фамилию автора, к которому адресован комментарий
            $answerCommentFIO = $author_comment_special["lastname"]." ".$author_comment_special["firstname"];
            // полное обращение
            preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}\s{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $valueComment["text"], $matches);
            // Обращение безя запятой
            if($matches != NULL) preg_match("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}/u", $matches[0], $matches_adress);
            // текст без обращения и запятой
            $valueComment["text"] = preg_replace("/^[0-9A-Za-zА-Яа-яЁё]{1,}[ ]{1}[0-9A-Za-zА-Яа-яЁё]{1,}[,]{1}[ ]{1}/u", "", $valueComment["text"]);
        }


        
        // print_r($albumInfo);
        $dateTimeComment = setRussianDate(date("j n Y H i", strtotime($valueComment["datetime"])));
        $comment = array(
            "idCommentAlbum" => $valueComment["id"],
            //"adress" => $matches_adress[0],
            "datetime" => $valueComment["datetime"],
            "datetimeToShow" => $dateTimeComment,
            "datetimeInSeconds" => strtotime(date($valueComment['datetime'])),
            "text" => $valueComment["text"],
            "countLike" => $valueComment["countlike"],
            "albumNNN_id" => $valueComment["album".$ProfileLoad->ID."_id"],
            "commentAlbumNNN_id" => $valueComment["commentalbum".$ProfileLoad->ID."_id"],
            "authorCommentID" => $valueComment["profile_id"],
            "authorCommentFIO" => $authorComment->FI(),
            "authorCommentPathAvatar" => PROJECT_PATH.$authorComment->ProfilePathAvatar(),
            "isProfileAuthSetLike" => ($like_com["count"]>0 ? true:false),
            "isProfileOwnerComment" => ($authorComment->ID == $idAuth ? true:false),
            "answerCommentId" => $answerCommentID,
            "answerAuthorCommentFIO" => $answerCommentFIO,
            "answerAuthorID" => $answerProfileID,
            "extension" => $valueComment["extension"],
            "photoPathAlbum" => '../img/album99.jpg',
        );
        if($comment["extension"] == null)
                {                                              
                    # Запрашиваем имя альбома для комментария
                    $albumInfo = $DB->getAll(QS::$getNameAlbum, $ProfileID, $valueComment["album".$ProfileLoad->ID."_id"]);
                    # Добавляем элемент массива nameAlbum
                    $comment["nameAlbum"] = $albumInfo[0]["name"];

                    $commentsAlbumTemp[$valueComment["id"]]=$comment;
                    $root_comment = $comment;
                }
        else if($comment["extension"] != null)
                {
                    $commentsAlbumTemp[$root_comment["idCommentAlbum"]]["text"] .= $comment["text"];
                }
    }

    // print_r($commentsAlbumTemp);
    // print_r($commentsPhotoTemp);
    $countCommentsAlbum = count($commentsAlbumTemp);


    if(isset($commentsPhotoTemp))
    {
        # Последний комментарий по дате
        $datetimePhotoLAST = current($commentsPhotoTemp);
        
        # Первый комментарий по дате
        $datetimePhotoFIRST = end($commentsPhotoTemp);
        
        # Формирование массива с ключом - дата в секундах
        foreach($commentsPhotoTemp as $key => $valueComment)
            $commentsPhoto[$valueComment['datetimeInSeconds']] = $valueComment;

        krsort($commentsPhoto);  
        
        unset($commentsPhotoTemp);

        # Пересоздаем темповый массив, но уже с ключами от 0 до n
        $i=0;
        foreach($commentsPhoto as $datetime => $valueComment)
        {
                $commentsPhotoTemp[$i] = $valueComment;
                $i++;
        }
    }


    if(isset($commentsAlbumTemp))
    {
        # Последний комментарий по дате
        $datetimeAlbumLAST = current($commentsAlbumTemp);

        # Первый комментарий по дате
        $datetimeAlbumFIRST = end($commentsAlbumTemp);
        
        # Формирование массива с ключом - дата в секундах
        foreach($commentsAlbumTemp as $key => $valueComment)
            $commentsAlbum[$valueComment['datetimeInSeconds']] = $valueComment;
        
        krsort($commentsAlbum);
        
        unset($commentsAlbumTemp);
    
        # Пересоздаем темповый массив, но уже с ключами от 0 до n
        $i=0;    
        foreach($commentsAlbum as $datetime => $valueComment)
        {
                $commentsAlbumTemp[$i] = $valueComment;
                $i++;
        }
    }

    
    if($countCommentsAlbum + $countCommentsPhoto != 0)
    {
        # Если количество найденных комментариев по сумме <= константного порога
        if($countCommentsAlbum + $countCommentsPhoto <= 5)
        {
            // print_r("111");
            if($countCommentsPhoto != 0)
                foreach($commentsPhoto as $datetime => $valueComment)
                    $allCommentsToSort[$datetime] = $valueComment;
            
            if($countCommentsAlbum != 0)
                foreach($commentsAlbum as $datetime => $valueComment)
                    $allCommentsToSort[$datetime] = $valueComment;

            krsort($allCommentsToSort);
        }

        # Если количество найденных комментариев по сумме > константного порога
        else
        {
            if($datetimeAlbumLAST['datetimeInSeconds'] > $datetimePhotoFIRST['datetimeInSeconds'])
            {
                // print_r("222");
                if($countCommentsAlbum == 5)
                {
                    // print_r("222.111");
                    foreach($commentsAlbum as $datetime => $valueComment)
                        $allCommentsToSort[$datetime] = $valueComment;

                }
                else
                if($countCommentsAlbum < 5)
                # Если количество комментариев к фото меньше константного порога,
                # то нужно добавить недостающее кол-во комментариев альбомов для константого порога
                {
                    // print_r("222.222");
                    $countAdd = 5 - $countCommentsAlbum;
                    foreach($commentsAlbum as $datetime => $valueComment)
                        $allCommentsToSort[$datetime] = $valueComment;

                    for($i = 0; $i < $countAdd; $i ++)
                        $allCommentsToSort[$commentsPhotoTemp[$i]["datetimeInSeconds"]] = $commentsPhotoTemp[$i];

                }



            }

            else
            if($datetimePhotoLAST['datetimeInSeconds'] > $datetimeAlbumFIRST['datetimeInSeconds'])
            {
                // print_r("333");
                if($countCommentsPhoto == 5)
                {
                    // print_r("333.111");

                    foreach($commentsPhoto as $datetime => $valueComment)
                        $allCommentsToSort[$datetime] = $valueComment;
                }
                
                else
                # Если количество комментариев к фото меньше константного порога,
                # то нужно добавить недостающее кол-во комментариев альбомов для константого порога
                if($countCommentsPhoto < 5)
                {
                    // print_r("333.222");
                    $countAdd = 5 - $countCommentsPhoto;
                    foreach($commentsPhoto as $datetime => $valueComment)
                        $allCommentsToSort[$datetime] = $valueComment;

                    for($i = 0; $i < $countAdd; $i ++)
                        $allCommentsToSort[$commentsAlbumTemp[$i]["datetimeInSeconds"]] = $commentsAlbumTemp[$i];

                }

            }
            else
            {
                // print_r("444");
                foreach($commentsPhoto as $datetime => $value)
                    $allCommentsToSort[$datetime] = $value;
                
                foreach($commentsAlbum as $datetime => $value)
                    $allCommentsToSort[$datetime] = $value;

                krsort($allCommentsToSort);

                # ВНИМАНИЕ!!! После применения следующей функции array_splice массив отрезается до константного значения
                # и индексация элементов начинается с 0 до 4.... вместо дат в секундах!!
                array_splice($allCommentsToSort, 5);
            }
        }
    }

    
    // print_r($allCommentsToSort);exit();
    // print_r($comments);exit();
    // $comment = array(
    //     "idComment" => "1",
    //     "idCommentAlbum" => "1",
    //     "photoPath" => "../img/album.jpg",
    //     "textComment" => "ного мокасинов",
    //     "textNameAlbum" => "В Дагестане много мокасинов",
    //     "authorCommentID" => "1",
    //     "authorCommentFIO" => "Адольф Габриэлович Гитлер",
    //     "authorCommentPathAvatar" => '../img/album1.jpg',
    //     "dateComment" => "13,17,2259",
    //     "countLikeComment" => "23",
    //     "isProfileAuthSetLike" => "1",
    //     "answerCommentId" => "1",
    //     "answerAuthorCommentFIO" => "Еся Виссарионович",
    //     "extension" => "",
    //     //"subCommnt" => array()
    // );

    
    // $allComments .= $commentsPhotos;
    // $allComments .= $commentsAlbums;
    // $count = 0;
    // foreach($allComments as $value)
    // {
    //     foreach($allComments as $value)
    //     {
    //         if ($count == 0)
    //         {
    //             $count++; 
    //             $datetime = $value['datetime'];
    //         }    
    //         else
    //             if($value['datetime'] < $firesDatetime)
    //             {
    //                 print_r("qwe");
    //             }
    //     } 
    // }


    // if($allComments)
    // {
    //     foreach($allComments as $key => $value)
    //         $allCommentsToSortTemp[strtotime(date($value['datetime']))] = $value;

    //     // print_r($allCommentsToSort);exit();
    //     krsort($allCommentsToSortTemp);

    //     $count = 1;
    //     foreach($allCommentsToSortTemp as $key => $value)
    //     {
    //         if($count <= 5)
    //         {
    //             $allCommentsToSort[$key] = $value;
    //             $count++;
    //         }
    //         else
    //             break;
    //     }

        $allComments = $allCommentsToSort;
        // print_r($allComments);exit();
        $smarty->assign('allComments', $allComments);
    // }
//$smarty->debugging = true;

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 1);
$smarty->assign('numTab', 52);

$block_include_head = $smarty->fetch("./include_head/UBMF/inc_photoAlbumComment.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionPhotoAlbumCommentSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUBMF_tpl = $smarty->fetch("./mainContent/union/topUGUBMF.tpl"); $smarty->assign('block_union_general', $topUGUBMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/photoAlbumComment.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>