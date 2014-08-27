<?php

# Блок получения списка классов школы    
    # Получаем idSchool
        if(isset($_GET['school']))
            $idSchool = $_GET['school'];
        else
            $idSchool = $Ko->idSchool;

    # Формирование отсортированного массива классов
        $getClass = $DB_DO->getAll(QSDO::$getClassOneSchool, $idSchool);
            if($getClass)
            {
                foreach($getClass as $key => $value)
                    $Classes[$value['level']][$value['letter']] = array();


                foreach($getClass as $key => $value)
                {   
                    $Classes[$value['level']][$value["letter"]]['id'] = $value["id"];
                    $Classes[$value['level']][$value["letter"]]['name'] = $value["name"];
                }
                ksort($Classes);
            }

# Блок формаирования списка предметов и журналов
    
    # Проверка на наличие idClass в $_GET

        # Получение idClass для первой загрузки страницы
            if(isset($_GET['class']))
                $idClass = $_GET['class'];
            else
            # Получаем только первый по списку класс из массива Classes    
                if($Classes)
                    foreach($Classes as $keyLevel => $level)
                    {
                        foreach($level as $keyLetter => $letter)
                        {
                            $idClass = $letter['id'];
                            break;
                        }
                        break;
                    }
    
    # Формируем список предметов выбранного класса
        $subjects = ClassS::getSubjectsStudyClassSTATIC($idClass);

    # Формирование списка Учеников и их Родителей
        if(isset($_GET['plist']) && $_GET['plist'] == 'true')
        {   
            # Массив учеников
                $learnerList = ClassS::getLearnersClassSTATIC($idClass);

            foreach($learnerList as $idLearner => $value)
            {
                $parentLearnerlist[$idLearner] = array(
                                'idProfile' => $value->idProfile,
                                'idLearner' => $idLearner,
                                'fio' => $value->FIO(),
                                'responsibles' => array()
                                );
                
                $pOneLearner = $DB_DO->getAll(QSDO::$getParentsOneLearner, $idLearner);
                
                if(!empty($pOneLearner))
                    foreach($pOneLearner as $key => $value)
                    {
                        $parentObject = new Responsible($value['id']);
                        $parentsOneLearner[$value['id']]['id'] = $value['id'];
                        $parentsOneLearner[$value['id']]['idProfile'] = $parentObject->idProfile;
                        $parentsOneLearner[$value['id']]['fio'] = $value['fio'];
                    }
                else
                    $parentsOneLearner['error'] = 'Нет родителя';
                
                $parentLearnerlist[$idLearner]['responsibles'] = $parentsOneLearner;
                
                unset($parentsOneLearner);
            }
            
            $smarty->assign('choosenMenu','listResponsible');
            $smarty->assign('parentLearnerlist',$parentLearnerlist);
        }
        else

    # Формирование Журнала для ВЫБРАННОГО предмета    
        if(isset($_GET['subject']) && $_GET['subject'] != NULL)
        {
            $idSubject = $_GET['subject'];

            if($studyDuration = $DB_DO->getAll(QSDO::$getStudyDurationInfo, $idSchool))
            {
                $countStudyDays = $DB_DO->getAll(QSDO::$getCountStudyDays, $idSchool);

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
                            header("Location: /jlife/pages/404.php");     
                            return;
                        }

                $journalOneSubject = ClassS::getGroupsWithLearnersAndMarksBySubjectSTATIC($idClass,$idSubject, null, null, $massMonth['choosenMonthStart'], $massMonth['choosenMonthEnd']);

                // print_r($journalOneSubject);
                // exit();

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
        }
        else

    # Формироваие журнала для ВСЕХ предметов
        {   
            # Общий массив всего журнала выбранного класса
                $journalAllSubjects = array(
                                        'learners' => array(),
                                        'marks' => array()
                                            );

            # Получение списка учеников выбранного класса
                $learnersClass = ClassS::getLearnersClassSTATIC($idClass);
            
            # Присваиваем список учеников выбранного класса к общему массиву
                $journalAllSubjects['learners'] = $learnersClass;
            
            # Получаем список предметов для журнала выбранного класса
                $getSubgroupsIdClass = $DB_DO->getAll(QSDO::$getSubgroupsIdClass, $idClass);

                # Цикл для формирования названий предметов для всех групп выбранного класса
                    foreach($getSubgroupsIdClass as $key => $value)
                    {
                        $subjectsOneClass[$value['subgroupId']] = $DB_DO->getRow(QSDO::$getSubjectsNameOneSubgroup, $value['subgroupId']);
                    }

            # Формирование массива средних оценок для каждого предмета каждого ученика выбранного класса
                foreach($learnersClass as $idLearner => $value)
                {
                    if(isset($subjectsOneClass))
                    foreach($subjectsOneClass as $idSubgroup => $value)
                    {
                        $checking = $DB_DO->getRow(QSDO::$checkIdSubgroupInIdLearner, $idSubgroup, $idLearner);
                        if($checking)
                        {
                            $avgabsmark = array(
                                        'averagemark' => $checking['averagemark'],
                                        'countmark' => $checking['countmark'],
                                        'countabsent' => $checking['countabsent'],
                                        'countabsentreason' => $checking['countabsentreason']
                                        );
                            $resultMark[$idLearner][$idSubgroup]['averagemark'] = round($avgabsmark['averagemark'],1);
                            $resultMark[$idLearner][$idSubgroup]['countmark'] = $avgabsmark['countmark'];
                            $resultMark[$idLearner][$idSubgroup]['countabsent'] = $avgabsmark['countabsent'];
                            $resultMark[$idLearner][$idSubgroup]['countabsentreason'] = $avgabsmark['countabsentreason'];
                        }
                        else
                        {
                            $resultMark[$idLearner][$idSubgroup]['error'] = 'Нет урока';
                        }
                    }
                }
                    $journalAllSubjects['marks'] = $resultMark;

        }
        
    $smarty->assign('subjectsOneClass',$subjectsOneClass);    
    $smarty->assign('subjects',$subjects);
    $smarty->assign('Classes', $Classes);
    $smarty->assign('actClass',$idClass);
    $smarty->assign('idClass',$idClass);
    $smarty->assign('idSchool',$idSchool);
    $smarty->assign('actSubj',$idSubject);
    $smarty->assign('journalAllSubjects',$journalAllSubjects);
    $smarty->assign('startYear',$START_EDUCATION_YEAR);
    $smarty->assign('journalOneSubject',$journalOneSubject);
    $smarty->assign('months',$months);
    $smarty->assign('actMon',$massMonth['choosenMonth']);
// $smarty->debugging=true;

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_journalKo.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$journalTeacherMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/journalKo.tpl"); $smarty->assign('block_mainField', $journalTeacherMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
