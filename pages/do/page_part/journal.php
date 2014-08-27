<?php 

$idSchool = $Learner->idSchool;
$classQ = $DB_DO->getAll(QSDO::$getInfoClass, $idSchool);
$smarty->assign('idSchool',$idSchool);


$idClass = $Learner->idClass;
$subjects = ClassS::getSubjectsStudyClassSTATIC($idClass);

$smarty->assign('subjects',$subjects);


if(isset($_GET['subject']))
{
    $idSubject = $_GET['subject'];
    
    # Прододжительность учебного семестра
    if($studyDuration = $DB_DO->getAll(QSDO::$getStudyDurationInfo, $idSchool))
    {
        # Количество рабочих дней в неделе
        $countStudyDays = $DB_DO->getRow(QSDO::$getCountStudyDays, $idSchool);

        # Начало/конец дат обучения, начало/конец месяца, начало/конец года обучения в семестре
        $START_EDUCATION_DATE = $studyDuration[0]["begin"];
        $END_EDUCATION_DATE = $studyDuration[0]["end"];

        $START_EDUCATION_MONTH = date("m", strtotime($START_EDUCATION_DATE)); // substr($START_EDUCATION_DATE,5,2);
        $START_EDUCATION_YEAR = date("Y", strtotime($START_EDUCATION_DATE)); // substr($START_EDUCATION_DATE,0,4);


        $END_EDUCATION_MONTH = date("m", strtotime($END_EDUCATION_DATE)); // substr($END_EDUCATION_DATE,5,2);
        $END_EDUCATION_YEAR = date("Y", strtotime($END_EDUCATION_DATE)); // substr($END_EDUCATION_DATE,0,4);

        $currentMonth = date("m"); // Формат месяца-> 01 - 12
        $currentDate = date("Y-m-d");
        $currentDay = date("j");

        # Если текущая дата < даты начала семестра && в массиве GET нет элемента 'month'
            if($currentDate < $START_EDUCATION_DATE && !isset($_GET['month']))
                $massMonth = getIntervalsMonths($START_EDUCATION_MONTH, $START_EDUCATION_YEAR);
            else 
        # Если текущая дата >= дате конца семестра && в массиве GET нет элемента 'month'
            if($currentDate >= $END_EDUCATION_DATE && !isset($_GET['month']))
                $massMonth = getIntervalsMonths($END_EDUCATION_MONTH, $START_EDUCATION_YEAR);
            else
        # Если текущая дата >= дате начала семестра && текущая дата <= дате конца семестра && в массиве GET нет элемента 'month'
            if($currentDate >= $START_EDUCATION_DATE && $currentDate <= $END_EDUCATION_DATE && !isset($_GET['month']))
                $massMonth = getIntervalsMonths($currentMonth, $START_EDUCATION_YEAR);
            else
        # Если в массиве GET есть элемента 'month' && месяц < числа 13 && > числа 0
            if(isset($_GET['month']) && $_GET['month'] < 13 && $_GET['month'] > 0)
                $massMonth = getIntervalsMonths($_GET['month'], $START_EDUCATION_YEAR);
            else
                {
                    header("Location: ".PROJECT_PATH."/pages/404.php");     
                    return;
                }

        # параметры null (idTeacher), null (idSubgroup) - пустые. Они используются в journalTeacher
        $learner_subgroup = ClassS::getGroupsWithLearnersAndMarksBySubjectSTATIC($idClass,$idSubject, null, null, $massMonth['choosenMonthStart'],
                                                                                 $massMonth['choosenMonthEnd']);

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

    $smarty->assign('actMon',$massMonth['choosenMonth']);
    $smarty->assign('actSubj',$idSubject);
	$smarty->assign('months',$months);
	$smarty->assign('learner_subgroup',$learner_subgroup);
    $smarty->assign('idSubject', $idSubject);
}

// $smarty->debugging=true;

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_journal.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionStudy.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_STUDY.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$classParentMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/journal.tpl"); $smarty->assign('block_mainField', $classParentMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>