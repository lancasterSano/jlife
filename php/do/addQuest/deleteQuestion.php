<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/y/QueryStorageY.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dby.php';

##############################################

if (isset ($_POST['idQuestion']))   { $idQuestion=$_POST['idQuestion'];}

    /*** Удаляем вопрос из таблицы @questions ***/
    $q = $DB_DO->query("CALL dQuestionOne(?i)", $idQuestion);
    /**************************************************/
    $idMaterial = $DB_DO->getOne("SELECT p.materialS_id1 FROM paragraphs p WHERE id = (SELECT p.paragraphS_id1 FROM partparagraphs p WHERE p.id = (SELECT q.partparagraphS_id1 FROM questions q WHERE q.id = ?i))", $idQuestion);
    $param = new QuestionManager();
    $param->removeQuestionMaterial($idQuestion, $idMaterial);
        
print json_encode($q);
?>