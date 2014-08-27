<?php
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

// объявим переменные
$classdaylessons = array();
$linkedgroups = array();

// получим значения из POST
if(isset($_POST["c"])){$idClass = $_POST["c"];}
if(isset($_POST["dn"])){$dayNumber = $_POST["dn"];}

// main
$days = array(
    1 => "Понедельник",
    2 => "Вторник",
    3 => "Среда",
    4 => "Четверг",
    5 => "Пятница",
    6 => "Суббота",
    7 => "Воскресенье",
);

// получить инфу о классе по иденификатору
$classFromDB = $DB_DO->getRow(QSDO::$getClass, $idClass);
$idSchool = $classFromDB["schoolS_id"];

// получаем все предметы школы (ид, имя, количество часов) и сохраняем в массив $subjects
$subjectsFromDB = $DB_DO->getAll(QSDO::$getSubjectsOfSchoolForSchedulePattern, $idSchool);
foreach($subjectsFromDB as $subjectFromDB){
    $subjects[$subjectFromDB["id"]] = array(
        "name" => $subjectFromDB["name"],
        "counthours" => $subjectFromDB["counthoursfirstsem"]
    );
}

// получаем все кабинеты в заданной школе (идентификатор и номер) и сохраняем в массив $classrooms
$classroomsFromDB = $DB_DO->getAll(QSDO::$getAllCabinetsOfSchool, $idSchool);
foreach($classroomsFromDB as $classroomFromDB){
    $classrooms[$classroomFromDB["id"]] = $classroomFromDB["number"];
}

// получаем всех учителей школы и сохраняем в массив $teachers
$teachersFromDB = $DB_DO->getAll(QSDO::$getAllTeachersIDs, $idSchool);
foreach($teachersFromDB as $teacherFromDB){
    $Teacher = new Teacher($teacherFromDB["id"]);
    $teachers[$teacherFromDB["id"]] = $Teacher;
}

// получаем уроки для текущего дня текущего класса
$lessonsFromDB = $DB_DO->getAll(QSDO::$getTimetableByClassAndDay, $idSchool, $idClass, $dayNumber);

// получаем группы этого класса
$subgroupsFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClass, $idClass);
if($subgroupsFromDB){
    foreach($subgroupsFromDB as $subgroupFromDB){
        $idsubgroup = $subgroupFromDB["id"];
        $idteacher = $subgroupFromDB["teacherS_id"];
        $idsubject = $subgroupFromDB["subjectS_id"];
        $subgroup = array(
            "name" => $subgroupFromDB["name"],
            "countownsubgroup" => $subgroupFromDB["countownsubgroup"],
            "subject"   => $subjects[$idsubject],
            "teacherFIO"   => $teachers[$idteacher]->FIOInitials(),
        );
        $subgroups[$idsubgroup] = $subgroup;
    }
} else {
    $subgroups[] = array();
}

// получаем список всех связанных групп в школе
$linkedsubgroupsFromDB = $DB_DO->getAll(QSDO::$getLinkedGroupsOfSchool, $idSchool);
foreach($linkedsubgroupsFromDB as $linkedsubgroupFromDB){
    $linkedgroups[$linkedsubgroupFromDB["idsubgroup1"]][] = $linkedsubgroupFromDB["idsubgroup2"];
}

// устанавливаем флаг наличия связанных групп у группы
foreach($subgroups as $idsubgroup => $subgroup){
    if(count($linkedgroups[$idsubgroup]) > 0) {
        $subgroups[$idsubgroup]["isLinked"] = 1;
    } else {
        $subgroups[$idsubgroup]["isLinked"] = 0;
    }
}

if(!$lessonsFromDB){// если уроков нет
    //заполняем 3 ячейки массива, как 3 пустых урока
    $classdaylessons[1][] = array("status" => "noLessonsDay", "isLinked" => 0, "number" => 1, "idsubgroup" => 0, "idcabinet" => 0);
    $classdaylessons[2][] = array("status" => "noLessonsDay", "isLinked" => 0, "number" => 2, "idsubgroup" => 0, "idcabinet" => 0);
    $classdaylessons[3][] = array("status" => "noLessonsDay", "isLinked" => 0, "number" => 3, "idsubgroup" => 0, "idcabinet" => 0);
} else {
    $lastnumber = 0;

    // в этом цикле мы добавляем в результируюший массив все уроки, которые есть в базе
    foreach ($lessonsFromDB as $lessonFromDB) {
        $idsubgroup = $lessonFromDB["subgroupS_id1"];
        $subgroup = $subgroups[$idsubgroup];
        if($lessonFromDB["classroomS_id1"] == NULL) {
            $idclassroom = 0;
        } else {
            $idclassroom = $lessonFromDB["classroomS_id1"];
        }
        if($idclassroom == 0){
            $cabinet = "";
        } else {
            $cabinet = $classrooms[$idclassroom];
        }

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
            "idsubgroup" => $idsubgroup,
            "isLinked" => $subgroup["isLinked"],
            "number" => $lessonFromDB["number"],
            "TeacherFIO" => $subgroup["teacherFIO"],
            "idclassroom" => $idclassroom,
            "cabinet" => $cabinet,
            "subjectname" => $nameSubject,
            "subjectCountHours" => $subgroup["subject"]["counthours"]
        );
        $classdaylessons[$lessonFromDB["number"]][$idsubgroup] = $lesson;
    }

    // здесь заполняем окнами недостающие уроки
    for($i = 1; $i <= $lastnumber; $i++){
        if($classdaylessons[$i]){

        } else {
            $classdaylessons[$i][0] = array(
                "status" => "window",
                "isLinked" => 0,
                "number" => $i,
                "idsubgroup" => 0,
                "idclassroom" => 0
            );
        }
    }

    // сортируем по ключу (номеру урока для правильного вывода)
    ksort($classdaylessons);

    // здесь мы заполняем связанные уроки, если они отсутствуют
    foreach ($lessonsFromDB as $lessonFromDB) {
        $idsubgroup = $lessonFromDB["subgroupS_id1"];
        $subgroup = $subgroups[$idsubgroup];
        if($subgroup["countownsubgroup"] > 1){
            foreach($linkedgroups[$idsubgroup] as $idlinkedgroup){
                if(!$classdaylessons[$lessonFromDB["number"]][$idlinkedgroup]){
                    $classdaylessons[$lessonFromDB["number"]][$idlinkedgroup] = array(
                        "status" => "window",
                        "isLinked" => 1,
                        "number" => $lessonFromDB["number"],
                        "idsubgroup" => $idlinkedgroup,
                        "idclassroom" => 0
                    );
                }
            }
        }
    }
}
// получить название дня по номеру
$dayname = $days[$dayNumber];

// получить количество смен в школе
$r = $DB_DO->getRow(QSDO::$getSchool, $idSchool);
$countshift = $r["shift"];


// сформировать результирующий массив
$response = array(
    "lessons" => $classdaylessons,
    "dayname" => $dayname, 
    "classname" => $classFromDB["name"],
    "shift" => $classFromDB["shift"],
    "countshift" => $countshift
);

// send response to client
print json_encode($response);
?>
