<?php
// DECLARE VARIABLES NEEDED LATER
for($i = 1; $i <= 12; $i++) {$allclasses[$i] = array();}

// GET PARAMETERS FROM ADRESS BAR
if(isset($_GET["ac"])) {$type = $_GET["ac"];}
else {$type = 1;}

switch($type){
    case null:
    case 1:
        // FORM RESULTING ARRAY
        $allclassesFromDB = $DB_DO->getAll(QSDO::$getAllClassesOfSchool, $Ko->idSchool);
        foreach ($allclassesFromDB as $classFromDB) {
            $idClass = $classFromDB["id"];
            $level = $classFromDB["level"];
            $idYoda = $classFromDB["yodaS_id"];
            if($idYoda) {
                $Yoda = new Yoda($idYoda);
            } else {
                $Yoda = NULL;
            }
            $allclasses[$level][$idClass] = $classFromDB;
            $allclasses[$level][$idClass]["Yoda"] = $Yoda;
            unset($Yoda);
        }
        break;
    case 2:
        // GET ALL TEACHERS OF SCHOOL
        $teachersFromDB = $DB_DO->getAll(QSDO::$getAllTeachers, $Ko->idSchool);
        foreach ($teachersFromDB as $teacherFromDB){
            $Teacher = new Teacher($teacherFromDB["id"]);
            switch($Teacher->category) {
                case 1:
                    $category = "Первая";
                    break;
                case 2:
                    $category = "Вторая";
                    break;
                case 3:
                    $category = "Высшая";
            }
            $teachers[] = array(
                "id" => $teacherFromDB["id"],
                "idschool" => $Teacher->idSchool,
                "FIO" => $Teacher->FIO(),
                "category" => $category
            );
        }
        break;
    case 3:
        // GET ALL SUBJECTS OF SCHOOL
        break;
    case 4:
        // GET ALL CABINETS OF SCHOOL
        break;
    default:
        
}

// EXTRA CODE FOR DEBUGGING
$smarty->debugging = false;

// ASSIGN VARIABLES TO SMARTY
$smarty->assign("allclasses", $allclasses);
$smarty->assign("teachers", $teachers);
$smarty->assign("idschool", $Ko->idSchool);
$smarty->assign("currentpagetype", $type);


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_groupsKo.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$trTMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/groupsKo.tpl"); $smarty->assign('block_mainField', $trTMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
