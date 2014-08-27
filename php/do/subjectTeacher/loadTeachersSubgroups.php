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
$idT = $_POST["idT"];
$idMaterial = $_POST["m"];
$Material = new Material($idMaterial);
$subgroupsFromDB = $DB_DO->getAll(QSDO::$getTeacherSubgroupsMaterial, $idT, $Material->idSubject);
foreach ($subgroupsFromDB as $subgroupFromDB) {
    $class = $DB_DO->getRow(QSDO::$getClassByIdGroup, $subgroupFromDB["id"]);
    if($subgroupFromDB["idmaterial"] == $idMaterial){
        $state = true;
    } else {
        $state = false;
    }
    if($subgroupFromDB["idmaterial"]){
        $hasMaterial = true;
    } else {
        $hasMaterial = false;
    }
    $subgroups[] = array(
        "id" => $subgroupFromDB["id"],
        "name"=> $class["name"]." ".$subgroupFromDB["name"], 
        "state"=> $state,
        "hasMaterial" => $hasMaterial
    );
}
$response = $subgroups;
print json_encode($response);
##############################################
?>
