<?php
// DECLARE VARIABLES NEEDED LATER
$materialsActive = array();
$materialsArchive = array();
define("ACTIVE", 31);
define("ARCHIVE_DELETED", 32);
define("ARCHIVE_UNASSIGNED", 33);

// GET NAMES OF SUBJECTS OF TEACHERS
$subjectNames = $Teacher->getStudySubjectNames();
$isMaterials = false;
$isArchiveMaterials = false;
if($subjectNames){
    $isSubjectsAssigned = true;
    foreach ($subjectNames as $subjectName) {
        $levelsFromDB = $DB_DO->getAll(QSDO::$getTeacherLevelsBySubject, $Teacher->idTeacher, $subjectName);
        $q = $DB_DO->getRow(QSDO::$getSubjectColor, $subjectName);
        $subjectcolor = $q["color"];
        $materialsActive[$subjectName]["color"] = $subjectcolor;
        foreach($levelsFromDB as $levelFromDB) {
            $level = $levelFromDB["level"];
            $complexitiesFromDB = $DB_DO->getCol(QSDO::$getTeacherComplexitiesBySubjectAndLevel, $Teacher->idTeacher, $subjectName, $level);
            foreach ($complexitiesFromDB as $complexityFromDB) {
                $idsubject = $DB_DO->getOne(QSDO::$getSubjectByNameAndLevelAndComplexity, $subjectName, $level, $complexityFromDB);
                $materialIDs = $DB_DO->getAll(QSDO::$getIDsOfMaterialsSubject, $idsubject, $Teacher->idTeacher, 0);
                foreach($materialIDs as $idMaterial) {
                    // GET MATERIAL OBJECT
                    $Material = new Material($idMaterial["id"]);
                    $countSubgroupsSubjectAssignedToTeacher = $DB_DO->getOne(QSDO::$getCountAvalaibleGroupsToAssign, $Teacher->idTeacher, $Material->idSubject);

                    // GET FIELDS FOR RESULTING ARRAY
                    $countsection = count($Material->getSectionsMaterial());
                    $countparagraphactive = $Material->countparagraphactive;
                    $countparagraph = $Material->countparagraph;
                    $dateupd = date("j n Y", strtotime($Material->dateupdate));
                    $date = setRussianDate($dateupd);
                    $deleted = $Material->deleted;
                    $notstudy = $Material->notstudy;

                    if(!$deleted && $notstudy) {
                        $isArchiveMaterials = true;
                        $state = ARCHIVE_UNASSIGNED;
                    } else if(!$deleted && !$notstudy){
                        $isMaterials = true;
                        $state = ACTIVE;
                    } elseif ($deleted) {
                        $isArchiveMaterials = true;
                        $state = ARCHIVE_DELETED;
                    }

                    switch ($state) {
                        case ACTIVE:
                            $q = $DB_DO->getAll(QSDO::$getClassesOfMaterial, $Material->idMaterial);
                            foreach ($q as $class){
                                $classes[] = array(
                                "id" => $class["id"],
                                "name" => $class["name"]
                                );
                            }
                            $materialsActive[$subjectName]["levels"][$level][] = array(
                                "id" => $Material->idMaterial,
                                "countsection" => $countsection,
                                "countparagraphactive" => $countparagraphactive,
                                "countparagraph" => $countparagraph,
                                "deleted" => $deleted,
                                "classes" => $classes,
                                "complexity" => $complexityFromDB
                            );  
                            break;

                        case ARCHIVE_DELETED:
                            $materialsArchive[$subjectName]["levels"][$level][] = array(
                                "id" => $Material->idMaterial,
                                "countsection" => $countsection,
                                "countparagraphactive" => $countparagraphactive,
                                "countparagraph" => $countparagraph,
                                "status" => "В корзине",
                                "date" => $date,
                                "complexity" => $complexityFromDB
                            );
                            break;

                        case ARCHIVE_UNASSIGNED:
                             $materialsArchive[$subjectName]["levels"][$level][]= array(
                                "id" => $Material->idMaterial,
                                "countsection" => $countsection,
                                "countparagraphactive" => $countparagraphactive,
                                "countparagraph" => $countparagraph,
                                "status" => "Не назначен",
                                "date" => $date,
                                "complexity" => $complexityFromDB,
                                "countsubgroups" => $countSubgroupsSubjectAssignedToTeacher
                            );
                            break;

                        default:

                            break;
                    }
                    unset($classes);    
                }
            }
        }
    }
} else {
    $isSubjectsAssigned = false;
    $notices['MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS']["type"] = 3;
    $notices['MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS']["messages"][] = array(
        "title" => MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS_TITLE,
        "text" => MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS_TEXT
    );
}
      
//print_r($materialsArchive);

// ASSIGN VARIABLES TO SMARTY
$smarty->assign('materialsActive', $materialsActive);
$smarty->assign('materialsArchive', $materialsArchive);
$smarty->assign('isSubjectsAssigned', $isSubjectsAssigned);
$smarty->assign('isMaterials', $isMaterials);
$smarty->assign('isArchiveMaterials', $isArchiveMaterials);
$smarty->assign('notices', $notices);


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_subjectTeacher.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$subjectTeacherMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/subjectTeacher.tpl"); $smarty->assign('block_mainField', $subjectTeacherMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>