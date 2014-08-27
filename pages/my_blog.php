<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "Blog");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'Блог'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);
$posts = array();

$metka = array(
        "idBlogMetka" => "1",
        "titleMetka" => "Здоровье",
    );
$smarty->assign('metka',$metka);

$post = array(
        "idPost" => "5",
        "titlePost" => "самых крутых стартап-питчей",
        "textPost" => "Сервис для интерактивного проведения презентаций. Позволяет вовлекать аудиторию во время и после проведения презентации.
                Фичи:
                - использование смартфона как многофункционального пульта для презентации;
                - броадкаст слайдов на девайсы в аудитории (смартфоны, таблеты, ноутбуки\нетбуки);
                - возможность отсылать вопрос из аудитории спикеру на смартфон;
                - возможность проводить опрос (активация с пульта спикера на смартфоне) - это еще в разработке;
                - после проведения презентации генерируется слайдкаст презентации (смартфон спикера пишет звук и переключения слайдов и формирует слайдкаст по завершении презентации).",
        "authorID" => "34",
        "authorPostFIO" => "Максимов Петр Семёнович",
        "datePost" => "20.56.8555",
        "countLikePost" => "54",
        "countComments" => "20",
        "isProfileAuthSetLike" => "",
        "extension" => "",
        "source" => "http://www.sctest.org/kiev/",
        //"metkas" => $metkas,
    );
    
    


$smarty->assign('post',$post);

/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 21);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_blog.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionBlogSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/blog.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>