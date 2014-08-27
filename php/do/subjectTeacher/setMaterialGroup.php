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
$idMaterial = $_POST["m"];
$idGroup = $_POST["g"];
$notstudy = $_POST["ns"];
$state = $_POST["s"];
$Material = new Material($idMaterial);
$idTeacher = $Material->idTeacher;
if($notstudy == "0"){ // if material is active
    if($state == "true"){ // this material is set to this group
        // TODO - unset material from group
        $r = $DB_DO->query(QSDO::$unsetMaterialGroup, $idGroup);
        $newCountMaterialGroups = $DB_DO->getOne(QSDO::$getCountGroupsMaterial, $idMaterial);
        if(!$newCountMaterialGroups){
            $r = $DB_DO->query(QSDO::$deactivateMaterial, $idMaterial);
        }
    } else {
        $subgroupFromDB = $DB_DO->getRow(QSDO::$getSubgroupFull, $idGroup);
        if($subgroupFromDB["materialS_id"]){
            $r = $DB_DO->query(QSDO::$unsetMaterialGroup, $idGroup);
            $newCountMaterialGroups = $DB_DO->getOne(QSDO::$getCountGroupsMaterial, $subgroupFromDB["materialS_id"]);
            if(!$newCountMaterialGroups){
                $r = $DB_DO->query(QSDO::$deactivateMaterial, $subgroupFromDB["materialS_id"]);
            }
        }
        $r = $DB_DO->query(QSDO::$setMaterialGroup, $idMaterial, $idGroup);
        
        // SET MATERIAL IN Y
        $idsubgroupY = $DB_Y->getOne(QSY::$getSubgroupYIDFromSubgroupDOID, $idGroup);
        $r = $DB_Y->query(QSY::$setMaterialGroup, $idsubgroupY, $idMaterial, $idTeacher);
    }
} else { // if material is inactive
    // TODO - set material to group
    $subgroupFromDB = $DB_DO->getRow(QSDO::$getSubgroupFull, $idGroup);
    if($subgroupFromDB["materialS_id"]){
        $r = $DB_DO->query(QSDO::$unsetMaterialGroup, $idGroup);
        $newCountMaterialGroups = $DB_DO->getOne(QSDO::$getCountGroupsMaterial, $subgroupFromDB["materialS_id"]);
        if(!$newCountMaterialGroups){
            $r = $DB_DO->query(QSDO::$deactivateMaterial, $subgroupFromDB["materialS_id"]);
        }
    } 
    $r = $DB_DO->query(QSDO::$setMaterialGroup, $idMaterial, $idGroup);
    
    // SET MATERIAL IN Y
    $idsubgroupY = $DB_Y->getOne(QSY::$getSubgroupYIDFromSubgroupDOID, $idGroup);
    $r = $DB_Y->query(QSY::$setMaterialGroup, $idsubgroupY, $idMaterial, $idTeacher);
    
    $r = $DB_DO->query(QSDO::$activateMaterial, $idMaterial);
}
if($r)
    $response = array("notstudy" => $notstudy, "state" => $state);
else
    $response = false;
print json_encode($response);
##############################################
?>
