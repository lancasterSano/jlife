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
$smarty->assign('is_do', 1); $smarty->assign('block_page_sys', "schedulePattern");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);
/****** CONNECT TO DB_DO ******/ require_once PROJECT_PATH.'/include/dbdo.php';
// DEFAULT SMARTY ASSIGNS 
$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign("PROJECT_PATH", PROJECT_PATH);

//$smarty->debugging = true;

// Получаем роли Ko авторизированного пользователя
$koRoles = $ProfileAuth->getRolesByRole(ROLES::$Ko);
if(count($koRoles)){// попадаем сюда, если у пользователя есть хоть какие-то роли завуча
    $kosIdInSchools = array(); $schoolIds = array();
    // var_dump($koRoles);
    // получаем список идентификаторов школ, где пользователь является завучем
    foreach ($koRoles as $koRole) {
        $kosIdInSchools[$koRole["idschool"]] = $koRole["idadress"];
        $schoolIds[] = $koRole["idschool"];
    }
    
    // Получаем подробности по этим идентификаторам
    $schoolsFromDB = $DB_DO->getAll(QSDO::$getSchoolsArray, $schoolIds);
    foreach ($schoolsFromDB as $value) {
        // формируем массив ключ:значение, где ключ id школы, а значение - подробности о школе
        $KoSchools[$value["id"]] = $value;
    }
    
    // получаем id школы, пришедший нам методом GET
    // если ничего не пришло, берем первую попавшуюся школу, где он завуч
    $idschool = isset($_GET["school"]) ? $_GET["school"] : $schoolIds[0];
    
    // если id пришел, но у пользователя в данной школе нет полномочий, то шлем его на 403 (доступ закрыт)
    if($KoSchools[$idschool] == NULL) { header("Location: ".PROJECT_PATH."/pages/403.php"); }
    
    // БЛОК ОБЬЯВЛЕНИЯ ПЕРЕМЕННЫХ
    $classdaylessons = array();
    $subgroupsclasses = array();
    $workingdays = array();
    $classrooms = array();
    $subjects = array();
    $teachers = array();
    $lessons = array();
    $linkedgroups = array();
    $days = array(
        1 => "Понедельник",
        2 => "Вторник",
        3 => "Среда",
        4 => "Четверг",
        5 => "Пятница",
        6 => "Суббота",
        7 => "Воскресенье",
    );

    // БЛОК ПОЛУЧЕНИЯ ДАННЫХ ИЗ БД
     
    // получаем количество рабочих дней в школе
    $r = $DB_DO->getRow(QSDO::$getCountStudyDays, $idschool);
    $COUNT_WORK_DAYS = $r["countstudyday"];
    
    // узнаем сгенерировано расписание или нет
    $r = $DB_DO->getRow(QSDO::$getActualStudyDuration, $idschool);
    $isLessons = $r["isLessons"];

    // в зависимости от количества рабочих дней сформируем массив дней $workingdays
    for($i = 1; $i <= $COUNT_WORK_DAYS; $i++){
        $workingdays[$i-1] = array("number" => $i, "name" => $days[$i]);
    }

    // получаем шаблоны уроков для всей школы и 
    // сохраняем в массив $lessons 
    $lessonsFromDB = $DB_DO->getAll(QSDO::$getTimetableSchool, $idschool);
    foreach ($lessonsFromDB as $lessonFromDB) {
        $lessons[$lessonFromDB["dayweek"]][$lessonFromDB["classs_id"]][] = $lessonFromDB;
    }

    // получаем все кабинеты в заданной школе (идентификатор и номер) и сохраняем в массив $classrooms
    $classroomsFromDB = $DB_DO->getAll(QSDO::$getAllCabinetsOfSchool, $idschool);
    foreach($classroomsFromDB as $classroomFromDB){
        $classrooms[$classroomFromDB["id"]] = $classroomFromDB["number"];
    }
//    print_r($classrooms);
//    exit();

    // получаем все предметы школы (ид, имя, количество часов) и сохраняем в массив $subjects
    $subjectsFromDB = $DB_DO->getAll(QSDO::$getSubjectsOfSchoolForSchedulePattern, $idschool);
    foreach($subjectsFromDB as $subjectFromDB){
        $subjects[$subjectFromDB["id"]] = array(
            "name" => $subjectFromDB["name"],
            "counthours" => $subjectFromDB["counthoursfirstsem"]
        );
    }

    // получаем всех учителей школы и сохраняем в массив $teachers
    $teachersFromDB = $DB_DO->getAll(QSDO::$getAllTeachersIDs, $idschool);
    foreach($teachersFromDB as $teacherFromDB){
        $Teacher = new Teacher($teacherFromDB["id"]);
        $teachers[$teacherFromDB["id"]] = $Teacher;
    }

    // получаем все классы школы и для каждого класса получаем список всех его групп
    $classesSchoolFromDB = $DB_DO->getAll(QSDO::$getClassesSchool, $idschool);
    foreach($classesSchoolFromDB as $class){
        $idclass = $class["id"];
        $subgroupsFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idclass);
        if($subgroupsFromDB){
            foreach($subgroupsFromDB as $subgroupFromDB){
                $idsubgroup = $subgroupFromDB["id"];
                $idteacher = $subgroupFromDB["teacherS_id"];
                $idsubject = $subgroupFromDB["subjectS_id"];
                $subgroup = array(
                    "name" => $subgroupFromDB["name"],
                    "countownsubgroup" => $subgroupFromDB["countownsubgroup"],
                    "subject"   => $subjects[$idsubject],
                    // "teacherFIO"   => $teachers[$idteacher]->FIOInitials(),
                    "teacherFIO"   => ($teachers[$idteacher] == null ) ?  $teachers[$idteacher] : $teachers[$idteacher]->FIOInitials(),
                );
                $subgroupsclasses[$idclass][$idsubgroup] = $subgroup;
            }
        } else {
            $subgroupsclasses[$idclass] = array();
        }
    }
    
    // получаем список всех связанных групп в школе
    $linkedsubgroupsFromDB = $DB_DO->getAll(QSDO::$getLinkedGroupsOfSchool, $idschool);
    foreach($linkedsubgroupsFromDB as $linkedsubgroupFromDB){
        $linkedgroups[$linkedsubgroupFromDB["idsubgroup1"]][] = $linkedsubgroupFromDB["idsubgroup2"];
    }
//    print_r($linkedgroups);
//    exit();

    // устанавливаем флаг наличия связанных групп у группы
    foreach($classesSchoolFromDB as $class){
        $idclass = $class["id"];
        foreach($subgroupsclasses[$idclass] as $idsubgroup => $subgroupFromDB){
            if(count($linkedgroups[$idsubgroup]) > 0) {
                $subgroupsclasses[$idclass][$idsubgroup]["isLinked"] = 1;
            } else {
                $subgroupsclasses[$idclass][$idsubgroup]["isLinked"] = 0;
            }
        }
    }
    
    // формируем результирующий массив
    foreach($workingdays as $day){
        foreach($classesSchoolFromDB as $class){
            $lessonsFromDB = $lessons[$day["number"]][$class["id"]];
            if(!$lessonsFromDB){// если уроков нет
                //заполняем 3 ячейки массива, как 3 пустых урока
                $emptylesson = array("status" => "noLessonsDay", "isLinked" => 0);
                for($i = 1; $i <= 3; $i++){
                    $classdaylessons[$day["number"]][$class["id"]]["lessons"][$i][] = $emptylesson;
                }
            } else {
                $lastnumber = 0;
                
                // в этом цикле мы добавляем в результируюший массив все уроки, которые есть в базе
                foreach ($lessonsFromDB as $lessonFromDB) {
                    $idsubgroup = $lessonFromDB["subgroupS_id1"];
                    $subgroup = $subgroupsclasses[$class["id"]][$idsubgroup];
                    $idclassroom = $lessonFromDB["classroomS_id1"];
                    
                    // ищем номер последнего урока в дне (нужно для следующего цикла)
                    if($lessonFromDB["number"] > $lastnumber){
                        $lastnumber = $lessonFromDB["number"];
                    }
                    $tempString = $subgroup["subject"]["name"];
                    if(mb_strlen($tempString) > 16){
                        $nameSubject = mb_substr($tempString, 0, 15).".";
                    } else {
                        $nameSubject = $tempString;
                    }
                    if($subgroup["countownsubgroup"] > 1){
                        $nameSubject = $nameSubject." ".preg_replace('/^\D+/','', $subgroup["name"]);
                    }
                    
                    $lesson = array(
                        "status" => "lesson",
                        "id" => $lessonFromDB["id"],
                        "number" => $lessonFromDB["number"],
                        "TeacherFIO" => $subgroup["teacherFIO"],
                        "cabinet" => $classrooms[$idclassroom],
                        "subjectname" => $nameSubject,
                        "subjectCountHours" => $subgroup["subject"]["counthours"],
                        "isLinked" => $subgroup["isLinked"]
                    );
                    $classdaylessons[$day["number"]][$class["id"]]["lessons"][$lessonFromDB["number"]][$idsubgroup] = $lesson;
                }
                
                // здесь заполняем окнами недостающие уроки
                for($i = 1; $i <= $lastnumber; $i++){
                    if($classdaylessons[$day["number"]][$class["id"]]["lessons"][$i]){
                        
                    } else {
                        $classdaylessons[$day["number"]][$class["id"]]["lessons"][$i][0] = array("status" => "window", "isLinked" => 0);
                    }
                }
                
                // сортируем по ключу (номеру урока для правильного вывода)
                ksort($classdaylessons[$day["number"]][$class["id"]]["lessons"]);
                
                // здесь мы заполняем связанные уроки, если они отсутствуют
                foreach ($lessonsFromDB as $lessonFromDB) {
                    $idsubgroup = $lessonFromDB["subgroupS_id1"];
                    $subgroup = $subgroupsclasses[$class["id"]][$idsubgroup];
                    if($subgroup["countownsubgroup"] > 1){
                        foreach($linkedgroups[$idsubgroup] as $idlinkedgroup){
                            if(!$classdaylessons[$day["number"]][$class["id"]]["lessons"][$lessonFromDB["number"]][$idlinkedgroup]){
                                $classdaylessons[$day["number"]][$class["id"]]["lessons"][$lessonFromDB["number"]][$idlinkedgroup] = array("status" => "window", "isLinked" => 1);
                            }
                        }
                    }
                }
                
                foreach ($lessonsFromDB as $lessonFromDB) {
                    ksort($classdaylessons[$day["number"]][$class["id"]]["lessons"][$lessonFromDB["number"]]);
                }
            }
        }
    }
} else {// попадаем сюда, если у пользователя нет ролей завуча
    header("Location: ".PROJECT_PATH."/403.php");
}

//print_r($classdaylessons);
//exit();

// SMARTY ASSIGNS
$smarty->assign("idschool", $idschool);
$smarty->assign("classday", $classdaylessons);
$smarty->assign("days", $workingdays);
$smarty->assign("classes", $classesSchoolFromDB);
$smarty->assign("isGenerated", $isLessons);
// NOT TO LOG OUT
require_once("./../page_part/smarty_incut.php");

// $block_include_head = $smarty->fetch("./include_head/do/USMF/inc_addpar.tpl"); $smarty->assign('block_include_head', $block_include_head);

// $topUnionlim_tpl = $smarty->fetch("./mainContent/do/union/topUnion_addpar.tpl"); $smarty->assign('block_union_general', $topUnionlim_tpl);

// $classParentMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/addpar.tpl"); $smarty->assign('block_mainField', $classParentMainField_tpl);

// TEMPLATE ASSIGNING
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_schedulePattern.tpl"); $smarty->assign('block_include_head', $block_include_head);
$block_union_tpl = $smarty->fetch("./mainContent/do/union/topUnionSchedulePattern.tpl"); $smarty->assign('block_union_general', $block_union_tpl);
$block_mainfield_tpl = $smarty->fetch("./mainContent/do/mainfield/schedulePattern.tpl"); $smarty->assign('block_mainField', $block_mainfield_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("./pageDO.tpl");
?>
