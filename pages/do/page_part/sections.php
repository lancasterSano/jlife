<?php 
$expandsection = $_GET["es"];
if (isset($_GET["material"])){$idmaterial = $_GET["material"];}
else {
    $materialTeacher = $Teacher->getMaterialAll();
    $idmaterial = $materialTeacher[0];
}
$Material = new Material($idmaterial);
$idAuthor = $Material->idTeacher;
if($Teacher->idTeacher != $idAuthor) {
    header("Location: ".PROJECT_PATH."/pages/403.php");
} else {
    $idsubject = $Material->idSubject;
    $subjectFromDb = $DB_DO->getRow(QSDO::$getSubjectById, $idsubject);
    $subjectname = $subjectFromDb["name"];
    $subjectcolor = $subjectFromDb["color"];

    $sections = $Material->getSectionsMaterial();
    if($sections){
        foreach ($sections as $section) {
            $isSections = true;
            $countactiveparagraphs = 0;
            $paragraphs = $Material->getParagraphsSection($section["idSection"], 0);
            foreach($paragraphs as $paragraph) {
                if(!$paragraph->notstudy) { $countactiveparagraphs++; }
            }
            $upperCaseString = mb_strtoupper($section["name"]);

            $topicsFromDB = $DB_DO->getAll(QSDO::$getTopicsSectionNames, $section["idSection"]);
            if($topicsFromDB){
                foreach($topicsFromDB as $topicFromDB){
                    $sectiontopics[] = $topicFromDB["name"];
                }
            } else {
                $sectiontopics = NULL;
            }
            $requirementsFromDB = $DB_DO->getAll(QSDO::$getRequirementsSection, $section["idSection"]);
            $res = array(
                "id" => $section["idSection"],
                "name" => $section["name"],
                "nameUpperCase" => $upperCaseString,
                "number" => $section["number"],
                "paragraphs" => $paragraphs,
                "countactiveparagraphs" => $countactiveparagraphs,
                "requirements" => $requirementsFromDB,
                "counthours" => $section["counthours"],
                "topics" => $sectiontopics
            );
            unset($sectiontopics);
            if($section["deleted"]){
                $sectionsDeleted[] = $res;
            } else {
                $sectionsActive[] = $res;
            }
        }
    } else {
        $isSections = false;
        $notices['MW_SECTIONSMATERIAL_HAS_NO_SECTIONS']["type"] = 3;
        $notices['MW_SECTIONSMATERIAL_HAS_NO_SECTIONS']["messages"][] = array(
            "text" => MW_SECTIONSMATERIAL_HAS_NO_SECTIONS
        );
    }
}
$pageTitle = $subjectname." ".$ProfileAuth->FI();
$smarty->assign("PageTitle", $pageTitle);
$smarty->assign('activeTab', 2);
$smarty->assign('expandsection', $expandsection);
$smarty->assign('idschool', $curIDSchoolTeacher);
$smarty->assign('idmaterial', $idmaterial);
$smarty->assign('subjectname', $subjectname);
$smarty->assign('subjectcolor', $subjectcolor);
$smarty->assign('sectionsActive', $sectionsActive);
$smarty->assign('sectionsDeleted', $sectionsDeleted);
$smarty->assign('isSections', $isSections);
$smarty->assign('notices', $notices);


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_sections.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$sectionsMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/sections.tpl"); $smarty->assign('block_mainField', $sectionsMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
