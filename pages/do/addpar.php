<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
// require_once(PROJECT_PATH."/settings/l_const_do.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "addpar");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'Параграф '.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

if(!(isset($_GET["paragraph"])) || !(isset($_GET["school"])))
{
    //Update to something
    header("Location: ".PROJECT_PATH."/pages/404.php");     
    return;
}   
else
{
	$paragraph = $_GET["paragraph"];
	$school = $_GET["school"];
}

if ($school == NULL || $paragraph == NULL) {
    header("Location: ".PROJECT_PATH."/pages/404.php");
    return;
}

$par = $DB_DO->getRow(QSDO::$getParagraph, $paragraph);
if($par == NULL || $par["deleted"] == 1)
{
    //Update to something
    header("Location: ".PROJECT_PATH."/pages/404.php"); 
    return;
}
$role = $ProfileAuth->getRolesInSchool($school);

$sect = $DB_DO->getRow(QSDO::$getSection, $par['sectionS_id1']);
$ppar = $DB_DO->getAll(QSDO::$getPartParagraphArr, $paragraph);
$teach = $DB_DO->getRow(QSDO::$getTeacherFromParagraph, $par['materialS_id1']);
$subject = $DB_DO->getRow(QSDO::$getSubjectNameColor, $sect['subjectS_id1']);
$teacher = array(
				   "id" => $teach['id'],
				   
				   "name" => $teach['firstname']." ".$teach['lastname'],
   );
// var_dump($role);
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
if (count($role)==0 || $its == false)
{

    header("Location: ".PROJECT_PATH."/pages/403.php"); 
    return;
}
$section = array(
				   "id" => $sect['id'],
				   "number" => $sect['number'],
				   "name" => $sect['name'],
   );


$paragraph = array(
				   "id" => $paragraph,
				   "name" => $par['name'],
				   "number" => $par['number'],
				   "valid" => $par["valid"],
				   "countpart" => $par["countpart"],
   );


foreach ($ppar as $part) {

	$partparagraphs[] = array(
		   "id" => $part['id'],
		   "text" => $part['text'],
		   "name" => $part['name'],
		   "number" => $part['number'],
		   
	   );
    }

if(isset($_SESSION['action']))
	$_SESSION['action'] = "";

// $smarty->debugging=true;
$smarty->assign('school',$school);
$smarty->assign('teacher',$teacher);
$smarty->assign('section',$section);
$smarty->assign('paragraph',$paragraph);
$smarty->assign('partparagraphs',$partparagraphs);
$smarty->assign('subject',$subject);

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$smarty->assign('numTab', 1);

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_addpar.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionlim_tpl = $smarty->fetch("./mainContent/do/union/topUnion_addpar.tpl"); $smarty->assign('block_union_general', $topUnionlim_tpl);

$classParentMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/addpar.tpl"); $smarty->assign('block_mainField', $classParentMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("./pageDO.tpl");
?>