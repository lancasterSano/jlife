<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

##############################################
if (isset ($_POST['idParagraph']))   { $idParagraph=$_POST['idParagraph'];}
    
    // Запрашиваем домашнее задание из таблицы Lessons для конкретного урока
    $partParagraphs = $DB_DO->getAll(QSDO::$getPartParagraphsInParagraph, $idParagraph);

        // print_r($partParagraphs);
        // exit();
print json_encode($partParagraphs);
?>