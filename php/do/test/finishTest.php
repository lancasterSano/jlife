<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/y/QueryStorageY.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
/****** CONNECT TO DB_Y ******/ require_once PROJECT_PATH.'/include/dby.php';

if (isset ($_POST['idAuth']))   { $idProfileAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfileLoad=$_POST['idLoad'];}
if (isset ($_POST['paragraph']))   { $paragraph=$_POST['paragraph'];}
if (isset ($_POST['school']))   { $school=$_POST['school'];}
if (isset ($_POST['subject']))   { $subject=$_POST['subject'];}
if (isset ($_POST['material']))   { $material=$_POST['material'];}
if (isset ($_POST['userAnswers']))   { $userAnswers=$_POST['userAnswers'];}
if(isset ($idProfileAuth) && isset ($idProfileLoad) && $idProfileAuth == $idProfileLoad && isset ($paragraph) && isset ($subject) && isset ($material))
{
//    print_r($userAnswers);

    # Конвертация строки (пример) '7.1-6.2' в массив:
    # [0] => 7.1
    # [1] => 6.2
    $userAnswers = preg_split("/-{1}/", $userAnswers);

   # Конвертация из массива userAnswer (пример) [0] => 7.1 [1] => 6.2 в массив:
   # [7] => Array([0] => 1)
   # [6] => Array([0] => 2)
   foreach ($userAnswers as $key => $value) {
       $answers = preg_split("/\.{1}/", $value);
       $questionId[] = $answers[0];
       foreach ($answers as $key1 => $value1) {
           if($key1 != 0 )
            $incoming[$answers[0]][] = $value1;
       }
   }
    # Получаем массив правилных ответов из таблицы Answers
    $correctAnswers = $DB_DO->getAll(QSDO::$getCorrectAnswersArr, $questionId);
    $mark = 0;
    foreach ($correctAnswers as $key => $value) {
        $results[$value["questionS_id1"]][$value["id"]] = (string)$value["id"];
    }

    foreach ($results as $idQuestion => $massRightAnswers) {
        $count = 0;
        $corrAnsw = true;
        
        foreach ($massRightAnswers as $idAnswerKey => $idAnswer) {
            if( $idAnswer != $incoming[$idQuestion][$count])
                 {
                    $corrAnsw = false;
                 }
                 $count++;
        }
        
       if ($corrAnsw == true && count($massRightAnswers) == count($incoming[$idQuestion]))
       {
           $complexity = $DB_DO->getOne(QSDO::$getComplexity, $idQuestion);
           if($complexity != 1)
              $complexity = transformationComplexityMark($complexity);

           $incoming[$idQuestion]["mark"] = $complexity;
           $mark += $complexity;
       }
       else
       {
           $incoming[$idQuestion]["mark"] = 0;
       }
       
    }
    $res = $incoming;

    $learner = $ProfileAuth->getRoleByRoleInSchool(ROLES::$Learner, $school);

    if ($learner) {
        ########## Block by vladyc9 ###########
         $countTry = $DB_DO->getOne(QSDO::$getCountTry, $learner["idadress"], $paragraph);
          switch($countTry){
            case 0:$ratio = 1; break;
            case 1:$ratio = 0.7; break;
            case 2:$ratio = 0.5; break;
            default:$ratio = 0;
          }
          $idSubgroup = $DB_DO->getOne(QSDO::$getIdSubgroup, $subject, $material);
          if($ratio != 0)
            $DB_DO->query(QSDO::$summRating, $mark*$ratio, $learner["idadress"], $idSubgroup);
        ########## Block by vladyc9 ###########

         $p = $DB_DO->query(QSDO::$setMark, $mark, date("Y-m-d H-i-s"), intval($paragraph), intval($learner["idadress"]), intval($subject), intval($material));
    }

    ########## Block by vladyc9 ###########
    # idSection, по которому проходится тест
        $idSection = $DB_DO->getOne(QSDO::$getIdSection, $paragraph);
    # Количество параграфов в разделе
        $countParagraphsOneSection = $DB_DO->getOne(QSDO::$getCountParagraphsOneSection, $idSection);
    # Количество пройденных тестов по параграфам в разделе
        $countTestsOneLearnerInSection = $DB_DO->getOne(QSDO::$getCountTestsOneLearnerInSection, $learner["idadress"], $material, $idSection);

      $dateSection = date('z', strtotime($DB_Y->getOne(QSY::$getDateFromSectiontiming, $idSection, $learner['idadress'])));
      $curDate = date('z');
      
    # id ученика в базе Y
        $idLearnerY = $DB_Y->getOne(QSY::$getLearnerYIDFromLearnerDOID, $learner['idadress']);
    # id группы в базе Y
        $idSubgroupY = $DB_Y->getOne(QSY::$getSubgroupY, $subject, $idLearnerY);

    # Если разница дат прохождения и открытия раздела > 1 дня
        if(($dateSection - $curDate) > 1)
        {
        # Если количество тестов в section и количество пройденых в section учеником равны
          if($countTestsOneLearnerInSection == $countParagraphsOneSection)
          { 
          # Если записи о прохождении section экстерном нет в таблице
            if(!$DB_Y->getOne(QSY::$checkCompletedSection, $idLearnerY, $idSection))
            {
              $DB_Y->query(QSY::$addCompletedSection, $idLearnerY, $idSubgroupY, $idSection, $subject);
            }
          }
        }
    ########## Block by vladyc9 ###########
} 
else 
{
    $res ="error";
}
print json_encode($res);
?>