<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH."/settings/l_const_do.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
require_once(PROJECT_PATH.'/auth/protected/components/common/NumbersPages.php');

/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
if($_GET["school"] != NULL)
{
    $find = $DB_DO->getRow(QSDO::$getSchool, $_GET["school"]);
    if($find == NULL) header("Location: ".PROJECT_PATH."/pages/404.php");
}

$ProfileAuth = $UA->getProfile();
$smarty->assign('PROJECT_PATH', PROJECT_PATH);

//$smarty->debugging = true;

/*****  MAIN CONTENT ****/

// Определить обладает ли доступом к кабинету в роли "Учитель"
$teacherRoles = $ProfileAuth->getRolesByRole(ROLES::$Teacher);
// Определить обладает ли доступом к кабинету в роли "Ответственный"
$responsibleRoles = $ProfileAuth->getRolesByRole(ROLES::$Responsible);
// Определить обладает ли доступом к кабинету в роли "Директор"
//$directorRoles = $ProfileAuth->getRolesByRole(ROLES::$Director);
// Определить обладает ли доступом к кабинету в роли "Завуч"
$koRoles = $ProfileAuth->getRolesByRole(ROLES::$Ko);

// назначим тип доступа в зависимости от роли
if(!count($teacherRoles) && !count($responsibleRoles) && !(count($koRoles))) {
    $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "development");
    $smarty->assign('PageTitle', 'Раздел находится в разработке');
    require_once(PROJECT_PATH."/pages/development.php");
} else {
    // get default type
    if(!$koRoles) {
        if(!$teacherRoles) {
            $defaulttype = "l";
        } else {
            $defaulttype = "s";
        }
    } else {
        $defaulttype = "k";
    }
    // valid input parameter type (can be "k" - KO, "s" - TEACHER, "l" - RESPONSIBLE)
    // and set default type - KO
    if(isset($_GET["type"])) {
        $type = $_GET["type"];
        if(($type != "k") && ($type != "s") && ($type != "l")) {
            header("Location: ".PROJECT_PATH."/pages/404.php");
        }
    } else {
        $type = $defaulttype;
    }
    
    // All schools, where I am KO
    if($koRoles){
        $kosIdInSchools = array(); $koSchoolIds = array();
        foreach ($koRoles as $koRole) {
            $kosIdInSchools[$koRole["idschool"]] = $koRole["idadress"];
            $koSchoolIds[] = $koRole["idschool"];
        }    
        $schKoFromDB = $DB_DO->getAll(QSDO::$getSchoolsArray, $koSchoolIds);
        foreach ($schKoFromDB as $value) {
            $KoSchools[$value["id"]] = $value;
        }
        if($type == "k"){
            $curIDSchoolKo = isset($_GET["school"]) ? $_GET["school"] : $koSchoolIds[0];
            if(!in_array($curIDSchoolKo, $koSchoolIds)) { header("Location: ".PROJECT_PATH."/pages/403.php"); }
        } else {
            $curIDSchoolKo = $koSchoolIds[0];
        }
        $Ko = new Ko($kosIdInSchools[$curIDSchoolKo]);
    }
    
    // All schools, where I am teacher
    if($teacherRoles){
        $teachersIdInSchools = array(); $teacherSchoolIds = array();
        foreach ($teacherRoles as $teacherRole) {
            $teachersIdInSchools[$teacherRole["idschool"]] = $teacherRole["idadress"];
            $teacherSchoolIds[] = $teacherRole["idschool"];
        }    
        $schTeacherFromDB = $DB_DO->getAll(QSDO::$getSchoolsArray, $teacherSchoolIds);
        foreach ($schTeacherFromDB as $value) {
            $TeacherSchools[$value["id"]] = $value;
        }
        if($type == "s"){
            $curIDSchoolTeacher = isset($_GET["school"]) ? $_GET["school"] : $teacherSchoolIds[0];
            if(!in_array($curIDSchoolTeacher, $teacherSchoolIds)) { header("Location: ".PROJECT_PATH."/pages/403.php"); }
        } else {
            $curIDSchoolTeacher = $teacherSchoolIds[0];
        }
        $Teacher = new Teacher($teachersIdInSchools[$curIDSchoolTeacher]);
    }
    // All schools where I am responsible
    if($responsibleRoles){
        $Learners = array(); $learnervalid = false;
        $GET_idlearner = $_GET["learner"];
        $isFirstResponsibleFound = false;
        foreach ($responsibleRoles as $responsibleRole) {
            $idResponsible = $responsibleRole["idadress"];
            if(!$isFirstResponsibleFound) {
                $idFirstResponsible = $idResponsible;
            }
            $idlearnersFromDb = $DB_DO->getAll(QSDO::$getLearnersOfResponsible, $idResponsible);
            $numberLearner = 1;
            foreach($idlearnersFromDb as $idlearner) {
                if($idlearner["learnerS_id1"] == $GET_idlearner) $learnervalid = true;
                $ProfileLearner = new Learner($idlearner["learnerS_id1"]);
                $Learners[$idResponsible][] = $ProfileLearner;
                $responsiblesInSchools[$idResponsible]["learners"][$numberLearner] = $ProfileLearner;
                $numberLearner++;
            }
            $isFirstResponsibleFound = true;
        }    
        // установим текущего ученика. 
        if(isset($_GET["learner"])) {
            if($learnervalid) { //ученик является среди 
                $curIDLearner = $_GET["learner"];
//                foreach($Learners as $idResponsible => $LearnersResp){
//                    foreach ($LearnersResp as $Learner){
//                        if($Learner->idLearner == $curIDLearner){
//                            $idCurResponsible = $idResponsible;
//                        }
//                    }
//                }
                foreach($responsiblesInSchools as $idResponsible => $learnersResp){
                    foreach ($learnersResp["learners"] as $numberLearner => $Learner){
                        if($Learner->idLearner == $curIDLearner){
                            $idCurResponsible = $idResponsible;
                            $curNumberLearner = $numberLearner;
                        }
                    }
                }
            } else {
                header("Location: ".PROJECT_PATH."/pages/403.php");
                exit();
            }
        } else {
            $idCurResponsible = $idFirstResponsible;
            $tempLearnersProfile = $Learners[$idFirstResponsible][0];
            $tempLearnerID = $tempLearnersProfile->idLearner;
            $curIDLearner = $tempLearnerID;
        }
        $Responsible = new Responsible($idCurResponsible);
        $LearnerCurrent = new Learner($curIDLearner);
    }
    // assign variables to SMARTY
    if($type == "k") {
        $headerData = array(
            "role" => "k",
            "idschool" => $Ko->idSchool,
            "idadress" => $Ko->idKo,
            "idlearner" => null,
            "numberlearner" => null
        );
        $smarty->assign('numTab', $curIDSchoolKo);
    } else if($type == "s") {
        $headerData = array(
            "role" => "s",
            "idschool" => $Teacher->idSchool,
            "idadress" => $Teacher->idTeacher,
            "idlearner" => null,
            "numberlearner" => null
        );
        $smarty->assign('numTab', $curIDSchoolTeacher);
    } else if($type == "l") {
        $headerData = array(
            "role" => "l",
            "idschool" => $Responsible->idSchool,
            "idadress" => $Responsible->idResponsible,
            "idlearner" => $curIDLearner,
            "numberlearner" => $curNumberLearner
        );
        $smarty->assign('numTab', $curIDLearner);
    }
    
    $smarty->assign('ProfileAuth', $ProfileAuth);
    
    $smarty->assign('type', $type);
    
    $smarty->assign("responsiblesinschools", $responsiblesInSchools);
    $smarty->assign('KoSchools', $KoSchools);
    $smarty->assign('ProfileKo', $Ko);
    
    $smarty->assign('TeacherSchools', $TeacherSchools);
    $smarty->assign('ProfileTeacher', $Teacher);
    
    $smarty->assign('Learners', $Learners);
    $smarty->assign('currentLearner', $LearnerCurrent);
    
    if($type == "k") {
        switch($_GET["s"]){
            case null:
            case 1:
                $headerData["pageName"] = "classes";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 1);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "groupsKo");
                $smarty->assign('PageTitle', 'Группы ' .$ProfileAuth->FI());
                require_once("./page_part/groupsKo.php");
                break;
            case 2:
                $headerData["pageName"] = "schedule";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 2);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "scheduleKo");
                $smarty->assign('PageTitle', 'Расписание '.$ProfileAuth->FI());
                require_once("./page_part/scheduleKo.php"); break;
            case 3: 
                $headerData["pageName"] = "journal";
                $smarty->assign('cur_role_attr', $headerData);
               $smarty->assign('activeTab', 3);
               $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "journalKo");
               $smarty->assign('PageTitle', 'Журнал '.$ProfileAuth->FI());
               require_once("./page_part/journalKo.php");
               break;
            default :
                header("Location:".PROJECT_PATH."/pages/404.php");
        }
    } else if($type == "s") {
        switch($_GET["s"]){
            case null: 
            case 1:
                $headerData["pageName"] = "schedule";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 1);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "scheduleTeacher");
                $smarty->assign('PageTitle', 'Расписание ' .$ProfileAuth->FI());
                require_once("./page_part/scheduleTeacher.php");
                break;
            case 2:
                $headerData["pageName"] = "subjects";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 2);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "subjectTeacher");
                $smarty->assign('PageTitle', 'Предметы '.$ProfileAuth->FI());
                require_once("./page_part/subjectTeacher.php"); break;
            case 3: 
                $headerData["pageName"] = "journal";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 3);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "journalTeacher");
                $smarty->assign('PageTitle', 'Журнал '.$ProfileAuth->FI());
                require_once("./page_part/journalTeacher.php");	
                break;
            case 4:
                $headerData["pageName"] = "testresult";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 4);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "testResultTeacher");
                $smarty->assign('PageTitle', 'Результаты тестов ' .$ProfileAuth->FI());
                require_once("./page_part/testResultTeacher.php");
                break;
            case 21:
                $headerData["pageName"] = "sections";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 2);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "sections");
                $smarty->assign('PageTitle', 'Предметы '.$ProfileAuth->FI());
                require_once("./page_part/sections.php"); break;
//            case 6:
//                $smarty->assign('activeTab', 5);
//                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "journal");
//                $smarty->assign('PageTitle', 'Оповещения ' .$ProfileAuth->FI());
//                require_once("./page_part/journal.php");
                break;
            default :
            header("Location:".PROJECT_PATH."/pages/404.php");
        }
    } else if ($type == "l"){
        switch($_GET["s"]){
            case null: 
            case 1:
                $headerData["pageName"] = "schedule";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 1);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "scheduleResponsible");
                $smarty->assign('PageTitle', 'Расписание ' .$ProfileAuth->FI());
                require_once("./page_part/scheduleResponsible.php");
                break;
            case 2:
                $headerData["pageName"] = "subjects";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 2);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "subjectsResponsible");
                $smarty->assign('PageTitle', 'Предметы '.$ProfileAuth->FI());
                require_once("./page_part/subjectsResponsible.php"); break;
            case 3: 
                $headerData["pageName"] = "journal";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 3);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "journalResponsible");
                $smarty->assign('PageTitle', 'Журнал '.$ProfileAuth->FI());
                require_once("./page_part/journalResponsible.php");	
                break;
            case 4:
                $headerData["pageName"] = "testresult";
                $smarty->assign('cur_role_attr', $headerData);
                $smarty->assign('activeTab', 4);
                $smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "testResultResponsible");
                $smarty->assign('PageTitle', 'Результаты тестов ' .$ProfileAuth->FI());
                require_once("./page_part/testResultResponsible.php");
                break;
            default :
                header("Location:".PROJECT_PATH."/pages/404.php");
        }
    }
}
?>