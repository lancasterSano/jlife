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
if (isset ($_POST['idUser']))   { $idUser=$_POST['idUser'];}
if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}
if (isset ($_POST['idClass']))   { $idClass=$_POST['idClass'];}

    if(isset($idUser) && isset($idSchool) && isset($idClass))
    {
         $userFIO = $DB->getRow(QS::$getFIOUser, $idUser);

         $checkUserInRolesTable = $DB->getOne(QS::$checkUserInRolesTable, $idUser, $idSchool);
         if(!$checkUserInRolesTable)
         {
             $createLearner = $DB_DO->query("CALL cLearner(?i, ?s, ?s, ?s, ?i, ?i, @idLearner)", $idUser, $userFIO['firstname'], $userFIO['lastname'], $userFIO['middlename'], $idSchool, $idClass);

             $idLearner = $DB_DO->getOne("SELECT @idLearner as idLearner");

             # CREATE LEARNER Y #
             $r = $DB_Y->query(QSY::$createLearner, $idLearner);
             $idLearnerY = $DB_Y->getOne("SELECT @idLearner as idLearner");
             $groupsClassFromDb = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idClass);
             foreach ($groupsClassFromDb as $subgroup) {
                 $IDsubgroupY = $DB_Y->getOne(QSY::$getSubgroupYIDFromSubgroupDOID, $subgroup["id"]);
                 if($IDsubgroupY) $r = $DB_Y->query(QSY::$addLearnerToGroup, $idLearnerY, $IDsubgroupY);
             }
             # END CREATE LEARNER Y #
             
             $setSpisokClassLearner = $DB_DO->query("CALL setSpisokClassLearner(?i, ?i)", $idClass, $idLearner);
             
             $school = $DB_DO->getRow(QSDO::$getSchool, $idSchool);
             $schoolname = $school['name'];
             $setLearnerToRoles = $DB->query("CALL cRole(?i, ?i, ?i, ?i, ?s, ?s)", $idUser, 1, $idLearner, $idSchool, "Ученик", $schoolname);
        }
        else 
        {
            $parentLearnerlist='';
            return print json_encode($parentLearnerlist);
        }
    }
        if($setSpisokClassLearner)
        {
                # Блок пересоздания списка ученик - родитель
                require("requireLoadLearnerResponsibleList.php");
        }


print json_encode($parentLearnerlist);
?>