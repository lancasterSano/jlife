<?php
// DECLARE VARIABLES NEEDED LATER
$subgroupIDs = array();
$learners = array();
$datesForQuery = array();
$datesForSmarty = array();
$marks = array();

// EXTRA CODE FOR DEBUGGING
$smarty->debugging = false;

// GET PARAMETERS FROM ADRESS BAR
if (isset($_GET["m"])) {$monthGET = $_GET["m"];}
if (isset($_GET["g"])) {$groupGET = $_GET["g"];}

// CHECK IF MONTH IS VALID
if(isset($monthGET)) {
    if(($monthGET > 12) || ($monthGET < 1))
        header ("Location: ".PROJECT_PATH."/pages/404.php");
    else
        $monthToShow = $monthGET;
} else {
    $monthToShow = date("m");
}

// GET YEAR TO SHOW
$currentyear = date("Y");

// GET SUBGROUPS OF TEACHER
$subgroupsFromDB = $DB_DO->getAll(QSDO::$getTeacherSubgroups, $Teacher->idTeacher);
if(!$subgroupsFromDB){
    $isSubjectsAssigned = false;
    $notices['ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS']["type"] = 3;
    $notices['ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS']["messages"][] = array(
        "title" => ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS_TITLE,
        "text" => ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS_TEXT
    );
} else {
    $isSubjectsAssigned = true;
    foreach ($subgroupsFromDB as $subgroupFromDB) {
        $id = $subgroupFromDB["id"];

        // FORM ARRAY OF SUBGROUP'S ID
        $subgroupIDs[] = $id;

        // GET FIELDS NEEDED FOR RESULTING ARRAY
        $idclass = $subgroupFromDB["idclass"];
        $classFromDb = $DB_DO->getRow(QSDO::$getClass, $idclass);
        $nameclass = $classFromDb["name"];
        $idsubject = $subgroupFromDB["idsubject"];
        $idmaterial = $subgroupFromDB["idmaterial"];
        $subjectFromDb = $DB_DO->getRow(QSDO::$getSubjectById, $idsubject);
        $namesubject = $subjectFromDb["name"];

        // FORM RESULTING ARRAY FOR SMARTY
        $subgroups[$id] = array(
            "idsubject" => $idsubject,
            "namesubject" => $namesubject." ".$nameclass,
            "idmaterial" => $idmaterial,
        );
    }

    // CHECK IF GROUP IS VALID AND SET CURRENT GROUP
    if(isset($groupGET)) {
        $isValidGroup = false;
        if(in_array($groupGET, $subgroupIDs)) {
            $isValidGroup = true;
        }
        if($isValidGroup) {
            $currentGroup = $groupGET;
        } else {
            header("Location: ".PROJECT_PATH."/pages/403.php");
        }
    } else {
        $currentGroup = $DB_DO->getOne(QSDO::$getTeacherFirstSubgroup, $Teacher->idTeacher);
    }

    // SET CURRENT ID MATERIAL AND ID SUBJECT
    $idcurrentsubject = $subgroups[$currentGroup]["idsubject"];
    $idcurrentmaterial = $subgroups[$currentGroup]["idmaterial"];

    // GET ALL LEARNERS OF CURRENT GROUP
    $learnersFromDB = $DB_DO->getAll(QSDO::$getLearnersOfSubgroup, $currentGroup);
    if($learnersFromDB)
        $isLearnersInGroup = true;
    else
        $isLearnersInGroup = false;
    foreach($learnersFromDB as $learnerFromDB) {
        $ratingNumber[$learnerFromDB["learnerS_id1"]]['rating'] = $learnerFromDB["rating"]; // by vladyc9
        $idlearner = $learnerFromDB["learnerS_id1"];
        $learners[$idlearner] = new Learner($idlearner);

    //     GET TESTS OF LEARNER (ALL DATES TRYIN')
        $testsLearnerFromDB = $DB_DO->getAll(QSDO::$getLearnersTests, $idcurrentmaterial, $monthToShow, $currentyear, $idlearner);
        foreach ($testsLearnerFromDB as $testLearnerFromDB) {
            $date = $testLearnerFromDB["ddate"];
            $marks[$idlearner][$date]["maxmark"] = $testLearnerFromDB["maxmark"];
            $marks[$idlearner][$date]["counttry"] = $testLearnerFromDB["counttry"];
        }
    }

    # Проверка зашли ли мы в подменю "Результаты параграфов"
    if(!isset($_GET["detailInfo"]))
    {
        if($ratingNumber){
            arsort($ratingNumber);
            $position = 1;
            foreach($ratingNumber as $key => $value) {
                $ratingNumber[$key]['position'] = $position;
                $position++;
            }
        }

        // GET DATES OF MONTH TO SHOW
        $firstDateOfMonth = date('Y-m-d', mktime(0,0,0, $monthToShow, 1, $currentyear));
        $firstDateOfMonthSmarty = date('d.m', mktime(0,0,0, $monthToShow, 1, $currentyear));
        $lastDayOfMonth = date('t', mktime(0,0,0, $monthToShow, 1, $currentyear));
        $datesForQuery[] = $firstDateOfMonth;
        $datesForSmarty[] = $firstDateOfMonthSmarty;
        for ($i = 1; $i < $lastDayOfMonth; $i++) {
            if($i == 1) {
                $currentDate = date("Y-m-d", strtotime("+1 day", strtotime($firstDateOfMonth)));
            } else {
                $currentDate = date("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
            }
            $datesForQuery[] = $currentDate;
            $datesForSmarty[] = date("d.m", strtotime($currentDate));
        }
    }
    else
    {
        $detailInfo = $_GET["detailInfo"];

        # Получаем параграфы по конкретному материалу учителя
        $paragraphsQuery = $DB_DO->getAll(QSDO::$getParagraphsAllSections, $idcurrentmaterial);
        foreach($paragraphsQuery as $key => $value)
        {
            $paragraphs[$value["id"]] = $value;
            $allParagraphs[] = $value['id'];
        }

        # Если параграфы получили
        if($paragraphs)
        {
            if(isset($_GET["par"]) && $_GET["par"] != "")
                {
                    if(in_array($_GET["par"], $allParagraphs)) 
                    {
                        $idParagraph = $_GET["par"];
                    }
                    else
                        header("Location: ".PROJECT_PATH."/pages/403.php");
                }
            else 
                $idParagraph = $paragraphsQuery[0]['id'];


            $nameParagraph = $paragraphs[$idParagraph]["name"];
            $numberParagraph = $paragraphs[$idParagraph]["number"];
            $idSection = $paragraphs[$idParagraph]["sectionS_id1"];
            $nameSection = $DB_DO->getOne(QSDO::$getNameOneSection, $idSection);
            foreach($learners as $idLearner => $value)
            {
                # Получаем массив пройденных тестов для каждого ученика
                $massOfTestsQuery = $DB_DO->getAll(QSDO::$getTestsOneLearner, $idParagraph, $idLearner);

                foreach($massOfTestsQuery as $key => $value)
                {   
                    $date = date_create($value['datetime']);
                    $month = date_create($value['datetime']);
                    # Преобразовываем название месяца в русский текст
                    $month = setRussianMonth(date_format($month,"n"));

                    # Формируем конечную дату -> date_format(изменяемая дата, новый формат даты)
                    $dateLesson = date_format($date,"j ".mb_substr($month, 0,3));

                    $value['datetime'] = $dateLesson;
                    $massOfTests[$idLearner][$value['id']] = $value;
                }

                $countMarks = $DB_DO->getOne(QSDO::$getCountTry, $idLearner, $idParagraph);
                $maxMark = $DB_DO->getOne(QSDO::$getMaxMarkOfAllTests, $idLearner, $idParagraph);
                if(!$maxMark) $maxMark = 0;
                $massOfCountMaxMarks[$idLearner]['countMarks'] = $countMarks;
                $massOfCountMaxMarks[$idLearner]['maxMark'] = $maxMark;
            }
        }
    }
        $notices['MI_SUBJECT_HAS_NO_PARAGRAPHS'] = array('type' =>2,
                'messages' => array()
            );

    $smarty->assign("detailInfo", $detailInfo);
    $smarty->assign("countMarks", $countMarks);
    $smarty->assign("maxMark", $maxMark);
    $smarty->assign("massOfTests", $massOfTests);
    $smarty->assign("massOfCountMaxMarks", $massOfCountMaxMarks);
    $smarty->assign("numberParagraph", $numberParagraph);
    $smarty->assign("nameSection", $nameSection);
    $smarty->assign("idParagraph", $idParagraph);
    $smarty->assign("namesubject", $namesubject);
    $smarty->assign("paragraphs", $paragraphs);
    $smarty->assign("nameParagraph", $nameParagraph);
}

// ASSIGN VARIABLES TO SMARTY
$smarty->assign("curgroup", $currentGroup);
$smarty->assign("subgroups", $subgroups);
$smarty->assign("learners", $learners);
$smarty->assign("ddates", $datesForSmarty);
$smarty->assign("indexdates", $datesForQuery);
$smarty->assign("marks", $marks);
$smarty->assign("curmonth", $monthToShow);
$smarty->assign("idcurrentsubject", $idcurrentsubject);
$smarty->assign("idcurrentmaterial", $idcurrentmaterial);
$smarty->assign("ratingNumber", $ratingNumber);
$smarty->assign("isSubjectsAssigned", $isSubjectsAssigned);
$smarty->assign("isLearnersInGroup", $isLearnersInGroup);
$smarty->assign("notices", $notices);

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_testResultTeacher.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

 $trTMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/testResultTeacher.tpl"); $smarty->assign('block_mainField', $trTMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>