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
if (isset ($_POST['idSection']))   { $idSection=$_POST['idSection'];}
if (isset ($_POST['idSubgroup']))   { $idSubgroup=$_POST['idSubgroup'];}
    

    $paragraphs = $DB_DO->getAll(QSDO::$getParagraphsInSection, $idSection, $idSubgroup);

print json_encode($paragraphs);
?>