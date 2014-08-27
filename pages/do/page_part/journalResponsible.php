<?php

$Learner = new Learner($_GET['learner']);
$idSchool = $Learner->idSchool;

# Прододжительность учебного семестра
    $studyDuration = $DB_DO->getAll(QSDO::$getStudyDurationInfo, $idSchool);
# Количество рабочих дней в неделе
    $countStudyDays = $DB_DO->getRow(QSDO::$getCountStudyDays, $idSchool);

# Начало/конец дат обучения, начало/конец месяца, начало/конец года обучения в семестре
    $START_EDUCATION_DATE = $studyDuration[0]["begin"];
    $END_EDUCATION_DATE = $studyDuration[0]["end"];

    $START_EDUCATION_MONTH = date("m", strtotime($START_EDUCATION_DATE)); // substr($START_EDUCATION_DATE,5,2);
    $START_EDUCATION_YEAR = date("Y", strtotime($START_EDUCATION_DATE)); // substr($START_EDUCATION_DATE,0,4);

    $START_EDUCATION_DAY = date("d", strtotime($START_EDUCATION_DATE));

    $END_EDUCATION_MONTH = substr($END_EDUCATION_DATE,5,2);
    $END_EDUCATION_YEAR = substr($END_EDUCATION_DATE,0,4);


# Число ТЕКУЩЕГО месяца ( 01 - 12 )
    $currentMonth = date("m");
# ТЕКУЩАЯ дата ( 2013-09-01 )
    $currentDate = date("Y-m-d");
# Дата ТЕКУЩЕГО дня ( 01 - 31 )
    $currentDay = date("j");


if(isset($_GET['m']) && $_GET['m'] >= 13 || isset($_GET['m']) && $_GET['m'] <= 0)
{
    header("Location: ".PROJECT_PATH."/pages/404.php"); return;
}

# Блок формирования начала выбранного месяца, конца и номера месяца
    
    # Если текущий месяц == месяцу начала семестра && текущий день >= дня начала семестра
    if($currentMonth == $START_EDUCATION_MONTH && $currentDay >= $START_EDUCATION_DAY)
    {
        # Если в массиве GET нет элемента 'm'
        if(!isset($_GET['m']))
            $choosenMonth = $START_EDUCATION_MONTH;
        else
            $choosenMonth = $_GET['m'];

            $massMonth = getIntervalMonthDayForStartEducationMonth($choosenMonth, $START_EDUCATION_YEAR, $START_EDUCATION_DAY);
        $numberOfDay = $START_EDUCATION_DAY;
    }
    else
        # Если текущий месяц == месяцу начала семестра && текущий день < дня начала семестра
        if($currentMonth == $START_EDUCATION_MONTH && $currentDay < $START_EDUCATION_DAY)
            $educationNotStartYet = true;
    else
    {
        # Если текущая дата < даты начала семестра && в массиве GET нет элемента 'm'
            if($currentDate < $START_EDUCATION_DATE && !isset($_GET['m']))
                $massMonth = getIntervalMonthDayOnly($START_EDUCATION_MONTH, $START_EDUCATION_YEAR);
            else
        # Если текущая дата >= дате конца семестра && в массиве GET нет элемента 'm'
            if($currentDate >= $END_EDUCATION_DATE && !isset($_GET['m']))
                $massMonth = getIntervalMonthDayOnly($END_EDUCATION_MONTH, $START_EDUCATION_YEAR);
            else
        # Если текущая дата >= дате начала семестра && текущая дата <= дате конца семестра && в массиве GET нет элемента 'm'
            if($currentDate >= $START_EDUCATION_DATE && $currentDate <= $END_EDUCATION_DATE && !isset($_GET['m']))
                $massMonth = getIntervalMonthDayOnly($currentMonth, $START_EDUCATION_YEAR);
            else
        # Если в массиве GET есть элемента 'm' && месяц < числа 13 && > числа 0
            if(isset($_GET['m']) && $_GET['m'] < 13 && $_GET['m'] > 0)
                $massMonth = getIntervalMonthDayOnly($_GET['m'], $START_EDUCATION_YEAR);
            else
                {
                    header("Location: ".PROJECT_PATH."/pages/404.php");     
                    return;
                }
        $numberOfDay = 1;
    }
# Блок формирования дат месяца, которые являются рабочими днями недели

    if($currentMonth > $_GET['m'] )
        $countDaysInChoosenMonth = date("t", strtotime($START_EDUCATION_YEAR."-".$_GET['m']));

    if($currentMonth == $_GET['m'] || !isset($_GET['m']))
        $countDaysInChoosenMonth = $currentDay;

    for($day = $numberOfDay; $day <= $countDaysInChoosenMonth; $day++)
    {

            if(!isset($_GET['m']))
                $numberOfDay = date("N",strtotime($START_EDUCATION_YEAR."-".$currentMonth."-".$day));
            else
                $numberOfDay = date("N",strtotime($START_EDUCATION_YEAR."-".$_GET['m']."-".$day));

            if($numberOfDay <= $countStudyDays['countstudyday'])
            {
                if($day < 10)

                    $date[] = array(
                                'number'=>$day,
                                'date' => $massMonth['choosenMonth'].".0".$day,
                                'dateForTpl' => "0".$day.".".$massMonth['choosenMonth']
                                );
                else
                    $date[] = array(
                                'number'=>$day,
                                'date' => $massMonth['choosenMonth'].".".$day,
                                'dateForTpl' => $day.".".$massMonth['choosenMonth'],
                                );
            }
    }

    $journalResponsible = array(
                        'subjects' => array(),
                        'marks' => array()
                        );
    $marks = array();


    $avgCntAbs = $DB_DO->getAll(QSDO::$getAvgCntAbsEachSubject, $Learner->idLearner);
    foreach($avgCntAbs as $key => $value)
    {
        $avgCountAbsent[$value['subgroupS_id']] = $value;
    }

    $idSubgrIdSubj = $DB_DO->getAll(QSDO::$getIdSubgroupIdSubject, $Learner->idLearner);
    foreach($idSubgrIdSubj as $key => $value)
    {
        $idSubgroupIdSubject[$value['idSubgroup']] = $value;
    }

    # Получаем список предметов ученика
    $subj = $DB_DO->getAll(QSDO::$getSubjectsOneLearner, $Learner->idLearner);
    foreach($subj as $key => $value)
    {
        $subjects[$value['id']] = $value;
    }
    $resultsMarks = $subjects;

    if(!empty($idSubgroupIdSubject))
    {
        foreach($idSubgroupIdSubject as $key => $value)
        {
            $resultsMarks[$value['idSubject']]['countmark'] = $avgCountAbsent[$value['idSubgroup']]["countmark"];
            $resultsMarks[$value['idSubject']]['countabsent'] = $avgCountAbsent[$value['idSubgroup']]["countabsent"];
            $resultsMarks[$value['idSubject']]['averagemark'] = round($avgCountAbsent[$value['idSubgroup']]["averagemark"],1);
        }

        # Помещаем список предметов в общий массив
        foreach($subjects as $key => $subject)
            $journalResponsible['subjects'][$subject['id']] = $subject;

        $i = 1;
        foreach($subjects as $key => $subject)
        {
            if(isset($date))
            foreach($date as $d)
            {
                $checking = $DB_DO->getAll(QSDO::$checkLessonByDate, $START_EDUCATION_YEAR.".".$d['date'], $subject['id']);
                    if($checking)
                    {
                        $maxCountMarks = $DB_DO->getAll(QSDO::$getMaxCountMarks, $Learner->idLearner, $START_EDUCATION_YEAR.".".$d['date'], $subject['id']);
                        $marks[$subject['id']][$d['number']][$checking[0]['id']] = array(
                                            'maxMark' => $maxCountMarks[0]['valueMax'],
                                            'countMarks' => $maxCountMarks[0]['count']
                                                );
                    }
                    else
                    {
                        $marks[$subject['id']][$d['number']] = array(
                                                            'error' => 'Нет урока'
                                                );
                    }
            }

            $marks[$subject['id']]['avg']['countmark'] = $resultsMarks[$subject['id']]['countmark'];
            $marks[$subject['id']]['avg']['averagemark'] += $resultsMarks[$subject['id']]['averagemark'];
            $marks[$subject['id']]['avg']['countabsent'] += $resultsMarks[$subject['id']]['countabsent'];
        }

        $journalResponsible['marks'] = $marks;
    }

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

$smarty->assign('educationNotStartYet',$educationNotStartYet);
$smarty->assign('actMon',$massMonth['choosenMonth']);
$smarty->assign('months',$months);
$smarty->assign('startYear',$START_EDUCATION_YEAR);
$smarty->assign('date',$date);
$smarty->assign('journalResponsible',$journalResponsible);
$smarty->assign('resultsMarks',$resultsMarks);

// $smarty->debugging=true;

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_journalResponsible.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$journalTeacherMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/journalResponsible.tpl"); $smarty->assign('block_mainField', $journalTeacherMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
