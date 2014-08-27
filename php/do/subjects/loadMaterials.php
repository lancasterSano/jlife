<?php
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/do/UsersDO.class.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');

/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
##############################################
if (isset ($_POST['idMaterial']))   { $idMaterial=$_POST['idMaterial'];}
if (isset ($_POST['idSection']))   { $idSection=$_POST['idSection'];}

if(isset ($idMaterial) && !empty ($idMaterial))
{
	if(isset ($idSection) && !empty ($idSection))
    {
    	if($idSection != 'null')
        {
        	$paragraphs = Material::getParagraphsSection_StudySTATIC($idMaterial, $idSection, 0, 0);
            $rez = array("loadparagraphs", $paragraphs);
        }
        else $rez = array("loadparagraphs", null, false); 
    }
	else
	{
	    if($idMaterial != 'null')
	    {
	    	$sections = Material::getSectionsMaterialSTATIC($idMaterial);
			// $subject = $DB_DO->getRow(QSDO::$getSubjectById, $idSubject);

	        $rez = array("loadsections", $sections);
	    }
	    else $rez = array("loadsections", null, false); 		
	}
}
else $rez = array("unkmnown", null);
print json_encode($rez);
?>