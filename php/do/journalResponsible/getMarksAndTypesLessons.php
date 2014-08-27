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

if (isset ($_POST['idLearner']))   { $idLearner=$_POST['idLearner'];}
if (isset ($_POST['idLesson']))   { $idLesson=$_POST['idLesson'];}
    	
    	
        $MrkIdSLTId = $DB_DO->getAll(QSDO::$getMarksAndTypesLessons, $idLearner, $idLesson);
        foreach($MrkIdSLTId as $key => $value)
        {
            $MarksIdSpisokLTId[$value['spisoklessontypeS_id1']] = $value;
            $SLTId[] = $value['spisoklessontypeS_id1'];
        }
        
        $SLTIdLTId = $DB_DO->getAll(QSDO::$getSpisokLessonTypesIdLessonTypesId, $idLesson, $SLTId);
        foreach($SLTIdLTId as $key => $value)
        {
            $SpisokLessonTypeIdLessonTypeId[$value['id']] = $value;
        }
        
        $lT = $DB_DO->getAll(QSDO::$getLessonTypesNameAndId, $idLesson);
        foreach($lT as $key => $value)
        {
            $lessonTypes[$value['id']] = $value;
        }

        $lessonTypesAndMarks = $MarksIdSpisokLTId;

        foreach($SpisokLessonTypeIdLessonTypeId as $key => $value)
        {
            $lessonTypesAndMarks[$value['id']]['lessonType'] = $lessonTypes[$value['lessontypeS_id']]['description'];
        }
        
print json_encode($lessonTypesAndMarks);
?>