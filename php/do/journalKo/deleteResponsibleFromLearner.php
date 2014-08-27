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
if (isset ($_POST['idLearner']))   { $idLearner=$_POST['idLearner'];}
if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}
if (isset ($_POST['idClass']))   { $idClass=$_POST['idClass'];}
if (isset ($_POST['idResponsible']))   { $idResponsible=$_POST['idResponsible'];}

    if(isset($idLearner) && isset($idResponsible) && isset($idClass))
    {
        require("requireDeleteResponsibleFromLearner.php");
    }

            # Блок пересоздания списка ученик - родитель
            require("requireLoadLearnerResponsibleList.php");


print json_encode($parentLearnerlist);
?>