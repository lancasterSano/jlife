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
/****** CONNECT TO DB_Y ******/ require_once PROJECT_PATH.'/include/dby.php';

##############################################
if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}
if (isset ($_POST['idClass']))   { $idClass=$_POST['idClass'];}
if (isset ($_POST['massForDeleteResponsible']))  { $massForDeleteResponsible=json_decode(stripslashes($_POST['massForDeleteResponsible']), true);}
if (isset ($_POST['massForDeleteLearners']))   { $massForDeleteLearners=json_decode(stripslashes($_POST['massForDeleteLearners']), true);}
    

  
    if(isset($massForDeleteLearners) && isset($massForDeleteResponsible) && isset($idClass) && isset($idSchool))
    {
        foreach($massForDeleteLearners as $idLearner => $massResponsible)
        {
            require("requireDeleteLearnerFromClass.php");
        }

        foreach($massForDeleteResponsible as $idLearner => $massResponsible)
        {
            foreach($massResponsible as $key => $idResponsible)
            {
                require("requireDeleteResponsibleFromLearner.php");
            }
        }

    }
            # Блок пересоздания списка ученик - родитель
            require("requireLoadLearnerResponsibleList.php");


print json_encode($parentLearnerlist);
?>