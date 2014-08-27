<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "test");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'Тест '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

if(!(isset($_GET["paragraph"])) || !(isset($_GET["school"])))
{
    //Update to something
    header("Location: /jlife/pages/404.php");     
    return;
}   
else
{
	$paragraph = $_GET["paragraph"];
	$school = $_GET["school"];
}

if ($school == NULL || $paragraph == NULL) {
    header("Location: /jlife/pages/404.php");
    return;
}

$par = $DB_DO->getRow(QSDO::$getParagraph, $paragraph);
if($par == NULL || $par["deleted"] == 1)
{
    //Update to something
    header("Location: /jlife/pages/404.php"); 
    return;
}
$role = $ProfileAuth->getRolesInSchool($school);


$sect = $DB_DO->getRow(QSDO::$getSection, $par['sectionS_id1']);
$teach = $DB_DO->getRow(QSDO::$getTeacherFromParagraph, $par['materialS_id1']);
$subject = $DB_DO->getRow(QSDO::$getSubjectNameColor, $sect['subjectS_id1']);
$Questions = $DB_DO->getAll(QSDO::$getQuestionsArr, $paragraph, $paragraph, $paragraph);


$teacher = array(
				   "id" => $teach['id'],
				   
				   "name" => $teach['firstname']." ".$teach['lastname'],
   );
$its = false;
$teacherka = false;
$stud = false;
foreach ($role as $key => $value) {
	if ($value["role"] == ROLES::$Teacher && $value["idadress"] == $teach['id'])
	{
		$its = true;
	}
	if ($value["role"] == ROLES::$Learner)
	{
		$stud = true;
	}
	if ($value["role"] == ROLES::$Teacher)
	{
		$teacherka = true;
	}
}
if (count($role)==0 || $its == false && $teacherka == true && $stud == false || $par["notstudy"] == 1)
{
    header("Location: ".PROJECT_PATH."/pages/403.php"); 
    return;
}


	// header("Location: ".PROJECT_PATH."/pages/do/test.php?school=1&paragraph=2"); 
    // return;


$section = array(
				   "id" => $sect['id'],
				   "name" => $sect['name'],
				   "number" => $sect['number'],
				   
   );


$paragraph = array(
				   "id" => $paragraph,
				   "number" => $par['number'],
				   "name" => $par['name'],
				   "valid" => $par["valid"],
				   "material" => $par["materialS_id1"],
   );

if($par["valid"]!=0)
{

$questNumber= 0; 
 foreach ($Questions as $Question) {
 	$questNumber++;
 	$answNumber= 0; 
 	$Answers = $DB_DO->getAll(QSDO::$getAnswersArr, $Question['id']);
 	foreach($Answers as $Answer) {
 		$answNumber++;
 		
 		// if ($Answer['deleted']==0) {
 			$AnswerArr[] = array(
 				"id" => $Answer['id'],
 			 	"number" => $answNumber,
 			 	"text" => $Answer['text']
 			 	);
 			// }
        }
    $questionTextArr = $DB_DO->getAll(QSDO::$getQuestionExtensionArr, $Question['id']);
    $questionText = $Question['text'];
    foreach ($questionTextArr as $key => $value) {
    	$questionText = $questionText.$value["text"];
    }
 	$QuestionArr[] = array(
 	"id" => $Question['id'],	
 	"number" => $questNumber,
 	"text" => $questionText,
 	"answers" => $AnswerArr
 	);
 	unset($AnswerArr);
 	unset($questionText);
 }
}
// $learner["idadress"];
// $smarty->debugging=true;

$smarty->assign('teacher',$teacher);
$smarty->assign('section',$section);
$smarty->assign('paragraph',$paragraph);
$smarty->assign('school',$school);
$smarty->assign('subject',$subject);
$smarty->assign('Questions',$QuestionArr);

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$smarty->assign('numTab', 4);

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_test.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionlim_tpl = $smarty->fetch("./mainContent/do/union/topUnion_paragraph.tpl"); $smarty->assign('block_union_general', $topUnionlim_tpl);

$classParentMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/test.tpl"); $smarty->assign('block_mainField', $classParentMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("./pageDO.tpl");
?>