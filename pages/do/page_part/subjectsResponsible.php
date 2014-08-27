<?php
//***********	 Навигационная полоса	**************
// Определить класс: Object
 $Class = (isset($_GET["class"])) ? new ClassS($_GET["class"]) : $LearnerCurrent->getClass();
// Получить предыдущий и следйющий id классы [ idPREVClass : idNEXTClass ]
$ClassPN = $DB_DO->getRow("CALL getNextPrevIdClass(?i, ?i);", $Class->idClass,  $LearnerCurrent->idSchool);
// var_dump($ClassPN);
// $WC = array(0 => $ClassPN["idPREVClass"], 1 => $ClassPN["idNEXTClass"]);
// $ClassPN = $DB_DO->getRow(QSDO::$getClassArray, $WC);
$smarty->assign('PNCLASS', $ClassPN);
// Объект текущего отображаемого класса
$smarty->assign('CURCLASS', $Class);
// Получить предмет-группы-преода-id материала

//***********	 КОНТЕНТ	**************
$subjects = $Class->getSubjectTeacherMaterialStudy();  	// Все материалы что учат ученики класса (погруппно)
if($subjects){
    $isSubjects = true;
    $idSubjectGET = $_GET['subject']; 						// какой предмет раскрыть
    $idSubgroupGET = $_GET['group'];

    if(isset($subjects[$idSubgroupGET]["material"]["idMaterial"]))
    {

            $MaterialActive = new Material($subjects[$idSubgroupGET]["material"]["idMaterial"]);
            $MaterialActive_Section = $MaterialActive->getSectionsMaterial();
            $smarty->assign('material_id', $subjects[$idSubgroupGET]["material"]["idMaterial"]);
            $smarty->assign('material_sections', $MaterialActive_Section);
    }
} else {
    $isSubjects = false;
    $notices['MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS']["type"] = 3;
    $notices['MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS']["messages"][] = array(
        "title" => MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS_TITLE,
        "text" => preg_replace("/%CLASS/", $Class->name, MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS_TEXT)
    );
}
$smarty->assign('select_subject', $idSubjectGET);
$smarty->assign('select_subgroup',isset($idSubgroupGET)?$idSubgroupGET:NULL);
$smarty->assign('subjects',$subjects);
$smarty->assign("notices", $notices);
$smarty->assign("isSubjects", $isSubjects);

// $smarty->debugging = true;


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_subjectsResponsible.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/subjectsResponsible.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>