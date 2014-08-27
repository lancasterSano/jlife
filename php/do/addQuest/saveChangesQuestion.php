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

if (isset ($_POST['idQuestion']))   { $idQuestion=$_POST['idQuestion'];}
if (isset ($_POST['newText']))   { $newText=$_POST['newText'];}
if (isset ($_POST['complexity']))   { $complexity=$_POST['complexity'];}
if (isset ($_POST['idPartParagraph']))   { $idPartParagraph=$_POST['idPartParagraph'];}
    	
    	$newText = normalize(htmlspecialchars($newText));
    	# Если длина нового вопроса больше 256 символов
    	if( (mb_strlen($newText)-256) > 0 )
    	{
    		# Считаем количество полных строк
    		$countFullStrings = (int)(mb_strlen($newText)/256);

    		$massOfStrings = array();
	        for($i=0; $i<=$countFullStrings; $i++)
	        {
	            $text = null;
	            $text = mb_substr($newText, $i*256, 256);
	            $massOfStrings[$i] = $text;            
	        }

	        $massAllId = $DB_DO->getAll(QSDO::$getIdRootAndIdExtensionOneQuestion, $idQuestion, $idQuestion);

	        for($i=0; $i<=$countFullStrings; $i++)
	        {
	        	if($massAllId[$i]['id'])
	        		// обновляем
	        		$DB_DO->query(QSDO::$updateComplexityTextQuestion, $massOfStrings[$i], $complexity, $massAllId[$i]['id']);
	        	else
	        		// добавляем
	        		$DB_DO->query(QSDO::$addQuestionExtension, $massOfStrings[$i], $idPartParagraph, $complexity, $idQuestion);
	        }

	        $countMassAllId = count($massAllId);
	        $countMassOfStrings = count($massOfStrings);

	        # Разница в количестве старых записей и новых
	        $difference = ($countMassAllId - $countMassOfStrings);

	        if($difference > 0)
	        {
	        	for($i = $massAllId[$countMassAllId-$difference]; $i < $countMassAllId; $i++)
	        	{
	        		$DB_DO->query(QSDO::$deleteSomeExtensionsPartsOfQuestions, $idQuestion, $massAllId[$i]['id']);
	        	}
	        }
    	}
    	else
    	{
    		# Удаляем все расширяемые части вопроса
    		$DB_DO->query(QSDO::$deleteExtensionPartsOfQuestion, $idQuestion);

    		# Делаем UPDATE в базе главного поля вопроса (idRoot)
    		$q = $DB_DO->query(QSDO::$updateComplexityTextQuestion, $newText, $complexity, $idQuestion);
    	}	

print json_encode($q);
?>