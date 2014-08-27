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

if (isset ($_POST['idLesson']))   { $idLesson=$_POST['idLesson'];}
if (isset ($_POST['idPartParagraph']))   
{ 
    $idPartParagraph=$_POST['idPartParagraph'];
    $q = $DB_DO->query(QSDO::$addNewPartParagraph, $idLesson, $idPartParagraph);
}
    # Запрашиваем домашнее задание из таблицы Lessons для конкретного урока
    $hometaskOneLessson = $DB_DO->getAll(QSDO::$getHometaskOneLesson, $idLesson);
            # Цикл для переприсваивания массиву индекса id lesson'a
            foreach($hometaskOneLessson as $value)
            {
                if($value["hometask"] == null)
                    $value["hometask"] = "";
                $hFL[$value["id"]] = $value;
            }
            $hometaskOneLessson = $hFL;
            // print_r($hometaskOneLessson);            
    $paragraphsQuery = $DB_DO->getAll(QSDO::$getLessonParagraphsTeacher, $idLesson);

            // print_r($paragraphsQuery);
            // exit();
        if($paragraphsQuery)
            foreach($paragraphsQuery as $paragraph)
            {
                // get partparagraphs of paragraphs, studied at this lesson
                $partParagraphsQuery = $DB_DO->getAll(QSDO::$getLessonPartParagraphs, $idLesson, $paragraph["id"]);
                // print_r($partParagraphsQuery);
                foreach($partParagraphsQuery as $partParagraph) {
                    $partParagraphs[] = array(
                        "id" => $partParagraph["id"],
                        "number" => $partParagraph["number"]
                    );
                }

                $paragraphs[] = array(
                    "idParagraph" => $paragraph["id"],
                    "number" => $paragraph["number"],
                    "name" => $paragraph["name"],
                    "notstudy" => $paragraph["notstudy"],
                    "hometask" => $hometaskOneLessson[$idLesson],
                    "partparagraphs" => $partParagraphs
                );
                unset($partParagraphs);
                // unset($paragraph);
            }
        else $paragraphs = array(
                                array(
                            "hometask" => $hometaskOneLessson[$idLesson]
                            )
                    );
        // print_r($paragraphs);
        // exit();
print json_encode($paragraphs);
?>