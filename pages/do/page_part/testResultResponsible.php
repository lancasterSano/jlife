<?php
$YEAR_START = 2012;
$YEAR_END = 2020;
if(isset($_GET["y"])) {
    if(($_GET["y"] > $YEAR_END) || ($_GET["y"] < $YEAR_START)) {
        header("Location: ".PROJECT_PATH."/pages/403.php");
    } else {
        $yearToShow = $_GET["y"];
    }
} else {
    $yearToShow = date("Y");
}
if(isset($_GET["m"])) {
    if(($_GET["m"] > 12) || ($_GET["m"] < 1))
        header ("Location: ../404.php");
    else
        $monthToShow = $_GET["m"];
} else
    $monthToShow = date("m");

if($monthToShow < 9 && $monthToShow > 0) {
    $startyear = $yearToShow - 1;
    $endyear = $yearToShow;
} else if ($monthToShow >= 9 && $monthToShow <= 12) {
    $startyear = $yearToShow;
    $endyear = $yearToShow + 1;
}
//echo "Month - ".$monthToShow."<br>";
//echo "Year - ".$yearToShow."<br>";
$r = $DB_DO->getAll(QSDO::$getMarksByDaysNSubjects, $LearnerCurrent->idLearner, $monthToShow, $yearToShow);
if(!$r) $isTests = false;
else $isTests = true;
$subjectIDs = array();
$datesTry = array();
$resDatesTry = array();
$marks = array();
$subgroupsLearner = $LearnerCurrent->getSubgroup();
if($subgroupsLearner){
    $isSubjects = true;
    $subjectsFromDB = $DB_DO->getAll(QSDO::$getSubjectsIDsInSubgroups, $subgroupsLearner);
    foreach ($subjectsFromDB as $subjectFromDB) {
        $subjectIDs[] = $subjectFromDB["id"];
    }
    foreach($r as $v){
        $idsubject = $v["idsubject"];
        if(!in_array(date("d.m",strtotime($v["ddate"])), $resDatesTry)) {
            $resDatesTry[$v["ddate"]] = date("d.m",strtotime($v["ddate"]));
            $datesTry[] = $v["ddate"];
        }
        ksort($resDatesTry);
        $marks[$idsubject][$v["ddate"]] = array(
            "maxmark" => $v["maxmark"],
            "counttry" => $v["counttry"]
        );

    }
    //print_r($marks);
    //print_r($datesTry);

    $ratingQuery = $DB_DO->getAll(QSDO::$getRatingByOwnLearnerAndSubgroups, $subgroupsLearner, $LearnerCurrent->idLearner);
    $subjectsIdFromSubgroupsTable = $DB_DO->getAll(QSDO::$getSubjectsIDs, $subgroupsLearner);
    // print_r($subjectsIdFromSubgroupsTable);
    foreach ($ratingQuery as $key => $valueRating) {
        foreach($subjectsIdFromSubgroupsTable as $key => $valueSubject)
        {
            if($valueRating['subgroupS_id'] == $valueSubject['id'])
            {   
                $ratingFull[$valueSubject['subjectS_id']] = $valueRating; 
                break;
            }
        }
    }

    foreach($ratingFull as $key => $value) {
        $q = $DB_DO->query("SET @num := 0");
        $position = $DB_DO->getOne(QSDO::$getPositionLearnerFromEachSubgroup, $value['subgroupS_id'], $LearnerCurrent->idLearner);
        $ratingFull[$key]['position'] = $position;
    }


    $resultmarks = array();
    foreach ($subjectIDs as $subjectID) {
        $r = $DB_DO->getRow(QSDO::$getSubjectById, $subjectID);
        $group = $DB_DO->getOne(QSDO::$getSubgroupBySubject, $subjectID, $LearnerCurrent->idClass, $LearnerCurrent->idLearner);
        $subjects[] = array(
            "id" => $subjectID,
            "name" => $r["name"],
            "learnergroup" => $group
        );
        foreach($datesTry as $dateTry) {
            if(isset($marks[$subjectID][$dateTry]))
            {
                $resultmarks[$subjectID][$dateTry] = array(
                    "maxmark" => $marks[$subjectID][$dateTry]["maxmark"],
                    "counttry" => $marks[$subjectID][$dateTry]["counttry"]
                );
            }
            else
                $resultmarks[$subjectID][$dateTry] = array();
        }
    }
} else {
    $isSubjects = false;
    $notices['MW_TESTRESULT_LEARNER_HAS_NO_SUBJECTS']["type"] = 3;
    $notices['MW_TESTRESULT_LEARNER_HAS_NO_SUBJECTS']["messages"][] = array(
        "title" => MW_TESTRESULT_LEARNER_HAS_NO_SUBJECTS_TITLE,
        "text" => preg_replace("/%NAME/", $LearnerCurrent->FirstName, MW_TESTRESULT_RESPONSIBLE_HAS_NO_SUBJECTS_TEXT)
    );
}
//var_dump($resultmarks);
$smarty->assign("subjects", $subjects);
$smarty->assign("datesTry", $resDatesTry);
$smarty->assign("allmarks", $resultmarks);
$smarty->assign("isTests", $isTests);
$smarty->assign("startyear", $startyear);
$smarty->assign("endyear", $endyear);
$smarty->assign("yearofstart", $YEAR_START);
$smarty->assign("yearofend", $YEAR_END);
$smarty->assign("curmonth", $monthToShow);
$smarty->assign("rating", $ratingFull);
$smarty->assign("notices", $notices);
$smarty->assign("isSubjects", $isSubjects);


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_testResultResponsible.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$tResMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/testResultResponsible.tpl"); $smarty->assign('block_mainField', $tResMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>
