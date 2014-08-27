<?php

$idSchool = $Teacher->idSchool;

// $classQ = $DB_DO->getAll(QSDO::$getInfoClass, $idSchool);
$smarty->assign('idSchool',$idSchool);


$idTeacher = $Teacher->idTeacher;
    // print_r($idTeacher);
    
    # Получаем id, letter и level классов, в которых преподает учитель
    if($c = $DB_DO->getAll(QSDO::$getClassesTeacher, $idTeacher, $idSchool))
    {
        $subjects[$idTeacher] = array();
        foreach($c as $value)
        {
            $classesLetterLevel[$value["classId"]] = $value;
            $classes[$value["classId"]] = array();
        }

        # Получаем id подгруппы , name подгруппы, id класса и id предмета учителя
        $s = $DB_DO->getAll(QSDO::$getSubgroupsTeacher, $idTeacher, $idSchool);

        # Получаем названия предметов
        $sInfo = $DB_DO->getAll(QSDO::$getSubjectsName, $idTeacher);

        foreach($sInfo as $value)
        {
            $subjectsInfo[$value["subjectId"]] = $value;
        }

        foreach($s as $valueSubgroup)
        {
            $classes[$valueSubgroup["classId"]][$valueSubgroup["id"]] = $valueSubgroup;
            $classes[$valueSubgroup["classId"]][$valueSubgroup["id"]] += $classesLetterLevel[$valueSubgroup["classId"]];
            $classes[$valueSubgroup["classId"]][$valueSubgroup["id"]] += $subjectsInfo[$valueSubgroup["subjectId"]];            
        }
        
        $subjects[$idTeacher] = $classes;
    }

$smarty->assign('subjects',$subjects);

if(isset($_GET['subject']) && $_GET['subject']!= NULL && isset ($_GET['class']) && $_GET['class']!= NULL && isset ($_GET['subgroup']) && $_GET['subgroup']!= NULL)
{
    $idSubject = $_GET['subject'];
    $idClass=$_GET['class'];
    $idSubgroup=$_GET['subgroup'];

    $checkForIdTeacher = $DB_DO->getOne(QSDO::$checkRealTeacher, $idClass, $idSubject, $idSubgroup);
    // print_r($idTeacher);exit();
    # Проверка на принадлежность idTeacher к загружаемому журналу
    if($checkForIdTeacher != $idTeacher)
        header("Location: ".PROJECT_PATH."/pages/404.php");

    $subject[] = $subjects[$idTeacher][$idClass][$idSubgroup];
    $smarty->assign('subject',$subject);

    
    if($studyDuration = $DB_DO->getAll(QSDO::$getStudyDurationInfo, $idSchool))
    {   
        $START_EDUCATION_DATE = $studyDuration[0]["begin"];
        $END_EDUCATION_DATE = $studyDuration[0]["end"];

        $START_EDUCATION_MONTH = substr($START_EDUCATION_DATE,5,2);
        $START_EDUCATION_YEAR = substr($START_EDUCATION_DATE,0,4);

        $END_EDUCATION_MONTH = substr($END_EDUCATION_DATE,5,2);
        $END_EDUCATION_YEAR = substr($END_EDUCATION_DATE,0,4);

        $currentMonth = date("m"); // Формат месяца-> 01 - 12
        $currentDate = date("Y-m-d");
        $currentDay = date("j");

        # Если дата меньше даты начала семестра И нет выбранного месяца
            if($currentDate < $START_EDUCATION_DATE && !isset($_GET['month']))
                $massMonth = getIntervalsMonths($START_EDUCATION_MONTH, $START_EDUCATION_YEAR);
            else 
        # Если дата больше или равна дате конца семестра И нет выбранного месяца
            if($currentDate >= $END_EDUCATION_DATE && !isset($_GET['month']))
                $massMonth = getIntervalsMonths($END_EDUCATION_MONTH, $START_EDUCATION_YEAR);
            else 
        # Если дата больше или равна дате начала семестра И меньше или равна дате конца семестра И нет выбранного предмета
            if($currentDate >= $START_EDUCATION_DATE && $currentDate <= $END_EDUCATION_DATE && !isset($_GET['month']))
                $massMonth = getIntervalsMonths($currentMonth, $START_EDUCATION_YEAR);
            else 
        # Если выбран месяц И месяц определенно меньше числа 13 и определенно больше числа 0
            if(isset($_GET['month']) && $_GET['month'] < 13 && $_GET['month'] > 0)
                $massMonth = getIntervalsMonths($_GET['month'], $START_EDUCATION_YEAR);
            else
                {
                    header("Location: ".PROJECT_PATH."/pages/404.php");     
                    return;
                }

        $learner_subgroup = ClassS::getGroupsWithLearnersAndMarksBySubjectSTATIC($idClass, $idSubject, $idTeacher, $idSubgroup, $massMonth['choosenMonthStart'], $massMonth['choosenMonthEnd']);
        
        for ($i=$START_EDUCATION_MONTH; $i<=$END_EDUCATION_MONTH; $i++)
                {
                    if(strlen($i) != 2) // проверка для подстановки символа ноль "0" перед первой цифрой
                        $i = '0'.$i;

                    $m = setRussianMonth($i);
                    $months[$i] = array(
                                        "id" => $i,
                                        "name" => $m
                                    );
                }
    }
    
    # Конструкцию $notices можно объявить и в .tpl файле (в данном случае даже желательно).
    # ПРИМЕР:
    #       {$notices['MI_SCHOOL_HAS_NO_STUDYDURATION']['messages'][] = $smarty.const.MI_SCHOOL_HAS_NO_STUDYDURATION}
    #       {$notices['MI_SCHOOL_HAS_NO_STUDYDURATION']['type'] = 2}
    #
    # Конструкция $notices в .php файле
    # ПРИМЕР:
    #       $notices['MI_SCHOOL_HAS_NO_STUDYDURATION'] = array('type' =>2,
    #               'messages' => array(
    #                   // 0 => array('title' => MI_SCHOOL_HAS_NO_STUDYDURATION),
    #                   // 1 => array('title' => MI_SCHOOL_HAS_NO_STUDYDURATION)
    #               )
    #       );

    $smarty->assign('actMon',$massMonth['choosenMonth']);
    // $smarty->assign('actSubj',$idSubject);
    $smarty->assign('actSubgroup',$idSubgroup);
    $smarty->assign('actClass',$idClass);
    $smarty->assign('months',$months);
    $smarty->assign('learner_subgroup',$learner_subgroup);
    $smarty->assign('idSubject', $idSubject);
}
    $smarty->assign('notices', $notices);

// $smarty->debugging=true;

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_journalTeacher.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGDOUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGDOUSMFTeacher_tpl);


$journalTeacherMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/journalTeacher.tpl"); $smarty->assign('block_mainField', $journalTeacherMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
