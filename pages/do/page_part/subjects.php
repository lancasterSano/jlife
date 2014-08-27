<?php
//***********	 Навигационная полоса	**************
// Определить класс: Object
 $Class = (isset($_GET["class"])) ? new ClassS($_GET["class"]) : $Learner->getClass();
// Получить предыдущий и следйющий id классы [ idPREVClass : idNEXTClass ]
$ClassPN = $DB_DO->getRow("CALL getNextPrevIdClass(?i, ?i);", $Class->idClass,  $Learner->idSchool);
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
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_subjects.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionStudy.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGDOUSMF_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_STUDY.tpl"); $smarty->assign('block_union_general', $topUGDOUSMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/subjects.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>