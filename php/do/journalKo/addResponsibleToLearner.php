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

if (isset ($_POST['idUser']))   { $idUser=$_POST['idUser'];}
if (isset ($_POST['idSchool']))   { $idSchool=$_POST['idSchool'];}
if (isset ($_POST['idLearner']))   { $idLearner=$_POST['idLearner'];}
if (isset ($_POST['idClass']))   { $idClass=$_POST['idClass'];}

    if(isset($idUser) && isset($idSchool) && isset($idLearner))
    {
        $relation = (isset($_POST['idRelation']) && $_POST['idRelation']) ? $_POST['idRelation'] : 1;
        $userFIO = $DB->getRow(QS::$getFIOUser, $idUser);

        $checkUserInRolesTable = $DB->getOne(QS::$checkUserInRolesTable, $idUser, $idSchool);
        if(!$checkUserInRolesTable || $checkUserInRolesTable['role'] == 2 || $checkUserInRolesTable['role'] == 8 || $checkUserInRolesTable['role'] == 4)
        {
            $checkUserInResponsibleTable = $DB_DO->getOne(QSDO::$checkUserInResponsibleTable, $idUser, $idSchool);
            if(!$checkUserInResponsibleTable)
            {   
                $createResponsible = $DB_DO->query("CALL cResponsible(?i, ?s, ?s, ?s, ?i, @idResponsible)", $idUser, $userFIO['firstname'], $userFIO['lastname'], $userFIO['middlename'], $idSchool);
            
                $idResponsible = $DB_DO->getOne("SELECT @idResponsible as idResponsible");
            }
            else
            {
                $idResponsible = $checkUserInResponsibleTable;
                $incrementCountLearner = $DB_DO->query(QSDO::$incrementCountLearner, $idResponsible);
            }

            $checkResponsibleInSpisokTable = $DB_DO->getOne(QSDO::$checkUserInSpisokResponsibleLearnerTable, $idLearner, $idResponsible);

            if(!$checkResponsibleInSpisokTable)
            {
                $setLearnerToSpisokResponsibleLearners = $DB_DO->query("CALL setResponsible(?i, ?i, ?i)", $relation, $idLearner, $idResponsible);
                
                if(!$setLearnerToSpisokResponsibleLearners)
                    
                    return print json_encode('System error');

                
                $checkResponsibleInRolesTable = $DB->getOne(QS::$checkResponsibleInRolesTable, $idResponsible);

                if(!$checkResponsibleInRolesTable){
                    $school = $DB_DO->getRow(QSDO::$getSchool, $idSchool);
                    $schoolname = $school['name'];
                    $setResponsibleToRoles = $DB->query("CALL cRole(?i, ?i, ?i, ?i, ?s, ?s)", $idUser, 8, $idResponsible, $idSchool, "Родитель", $schoolname);
                }
            }
            else
                return print json_encode('Added yet');
        }
        else
            return print json_encode('Another role');
    }
        if($setLearnerToSpisokResponsibleLearners)
        {
                # Блок пересоздания списка ученик - родитель
                require("requireLoadLearnerResponsibleList.php");
        }


print json_encode($parentLearnerlist);
?>